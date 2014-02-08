function MamboSave(editor_id, html, body)
{
    if(convert_urls){
        base_url = tinyMCE.settings['mambo_base_url'];
        html = tinyMCE.regexpReplace(html, 'href\s*=\s*"?'+base_url+'', 'href="', 'gi');
        html = tinyMCE.regexpReplace(html, 'src\s*=\s*"?'+base_url+'', 'src="', 'gi');
        html = tinyMCE.regexpReplace(html, 'value\s*=\s*"?'+base_url+'', 'value="', 'gi');
        }
    return html;
}
