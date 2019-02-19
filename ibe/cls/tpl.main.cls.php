<?
/*
 * Revised: Apr 25, 2011
 */

class tmplAdmin {
    var $title;
    var $middle;
    var $body;
    var $scripts;

    function tmplAdmin() {
        $this->title = "";
        $this->middle = "&nbsp;";
        $this->body = "&nbsp;";
        $this->scripts = "";
    }
}
global $tmpl;
$tmpl = &New tmplAdmin;
?>