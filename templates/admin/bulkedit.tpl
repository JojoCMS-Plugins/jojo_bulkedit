{include file="admin/header.tpl"}

<style type="text/css">
{literal}

tr.row1 {
  background: #e5e5e5;
}

tr.row2 {
  background: #eee;
}

tr.hover {
    background: #f5f5f5;
}

label:hover {
  background: #ccc;
}
{/literal}
</style>

{if !$tablename}
<div class="info">
<p>Welcome to the bulk-edit page. This new functionality is designed to make small changes to lots of pages faster. However, it's very incomplete, and it has bugs. We have included the functionality in Jojo as-is, because it's still a really useful tool to have available.<br />
Please report any bugs you find on our bug tracker - <a href="http://bugs.jojocms.org" target="_BLANK">bugs.jojocms.org</a>, and we will address them in due course.</p>
<h3>How to use</h3>
<p>Firstly, add the tablename that you want to edit to the URL. If you wanted to edit pages, you would go to <a href="admin/bulk-edit/page/">{$SITEURL}/admin/bulk-edit/page/</a> or to edit articles, <a href="admin/bulk-edit/article/">{$SITEURL}/admin/bulk-edit/article/</a>. Future releases will include links to all the useful tables.</p>
<p>Next, select the fields you wish to edit using the checkboxes at the top of the screen, then press update. A form should appear showing those fields in an editable fashion.</p>
</div>
{else}

<div style="border: 1px solid #ccc; margin: 5px; padding: 5px;">
  <h3>Select fields to display</h3>
  <form method="post" action="{$REQUEST_URI}">
  {section name=f loop=$allfields}
  <label><input type="checkbox" name="setfields_{$allfields[f].fieldname}" id="setfields_{$allfields[f].fieldname}" value="setfields"{if $allfields[f].active} checked="checked"{/if} /> {$allfields[f].name}</label>
  {/section}
  <input type="submit" name="setfields" value="Update" />
  </form>
</div>
<div style="margin:5px;">{$pagination}</div>
<table class="stdtable" style="margin:5px;">
<thead>
  <tr class="{cycle values='row1,row2'}">
    <th>&nbsp;</th>
    {foreach from=$activefields key=k item=v}
    <th>{$v}</th>
    {/foreach}
    <th>Edit</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
{section name=r loop=$records}
  <tr class="{cycle values='row1,row2'}">
    <form method="post" action="actions/admin-bulk-save.php" target="frajax-iframe">
    <th style="text-align: left"><input type="hidden" name="tablename" value="{$tablename}" /><input type="hidden" name="id" value="{$records[r].id}" />{section name=i loop=$records[r].depth}&nbsp;&nbsp;&nbsp;{/section} {$records[r].title}</th>

    {foreach from=$records[r].fields key=k item=v}
     <td style="text-align: center;">{$v}</td>
    {/foreach}
    <td style="text-align: center"><a href="admin/edit/{$tablename}/{$records[r].id}/" title="Use the full editor on this {$tablename}">Edit</a></td>
    <td><input type="submit" name="save" value="Save" /></td>
    </form>
  </tr>
{/section}
</tbody>
</table>
{/if}
{include file="admin/footer.tpl"}