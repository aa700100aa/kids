<?php
class AddRewriteRules{
    private $rule     = null;
    private $query    = null;
    private $callback = null;

    function __construct($rule, $query, $callback){
        $this->rule     = $rule;
        $this->query    = $query;
        $this->callback = $callback;
        add_filter('query_vars', array(&$this, 'query_vars'));
        add_filter('rewrite_rules_array', array(&$this, 'rewrite_rules_array'));
        add_action('init', array(&$this, 'init'));
        add_action('wp', array(&$this, 'wp'));
    }

    public function init(){
        global $wp_rewrite;
        $rules = $wp_rewrite->wp_rewrite_rules();
        if (!isset($rules[$this->rule])) {
            $wp_rewrite->flush_rules();
        }
    }

    public function rewrite_rules_array($rules){
        global $wp_rewrite;
        $rule = rtrim($this->rule,'$');
        $new_rules["{$rule}$"] = $wp_rewrite->index . '?'.$this->query.'=1';
        $new_rules["{$rule}/page/([0-9]{1,})/?$"] = $wp_rewrite->index . '?'.$this->query.'=1' . '&paged=$matches[1]';
        $rules = array_merge($new_rules, $rules);
        return $rules;
    }

    public function query_vars($vars){
        $vars[] = $this->query;
        return $vars;
    }

    public function wp(){
        if (get_query_var($this->query)) {
            call_user_func_array($this->callback,array($this));
        }
    }
}
