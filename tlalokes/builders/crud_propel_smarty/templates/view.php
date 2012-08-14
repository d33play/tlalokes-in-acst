<?
$str = <<<DOT
{include file='head.tpl'}
<table class="list" align="center">
{if \$exception}
  <tr>
    <td class="data">
      <p align="center">{\$exception}</p>
      <p align="center"><a href="javascript:history.back();">{\$back}</a></p>
    </td>
  </tr>
{else}
{if \$_action == 'read' || \$_action == 'filter' || \$_action == 'create' || \$_action == 'update' || \$_action == 'delete' }
{if !\$_id}
  <tr>
    <td class="title">
      <div id="title">{\$title}</div>
      <div align="right">
        <a href="{\$uri}">{\$home}</a> &nbsp;
        <a href="{\$uri}{$name}/add">{\$add}</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="filter">
      <form method="get" action="{\$uri}{$name}/filter{if \$pager.limit}/limit/{\$pager.limit}{/if}">

DOT;

$def = '';
foreach ( $columns as $key => $column ) {
  $def .= "        <div class=\"formElement\">\n" .
          "          <label for=\"$key\">{\$$key}</label>\n";
  // looks for references
  if ( isset( $column->reference ) && $column->reference ) {
    $def .= "          <select name=\"$key\">\n".
            "            <option value=\"\">Select an option</option>\n" .
            "{foreach from=\${$column->reference->table} item=item}\n".
            "            <option value=\"{\$item.id}\">{\$item.id}</option>\n".
            "{/foreach}\n".
            "          </select>\n";
  } else {
    $def .= "          <input type=\"text\" name=\"$key\" />\n";
  }
  $def .= "        </div>\n";
}

$str .= $def . <<<DOT
        <div class="formElement">
          <input type="submit" id="submit" value="{\$filter}" />
        </div>
      </form>
    </td>
  </tr>
  <tr>
    <td class="paging">
      <form method="get" action="{\$uri}{$name}/{if \$_action == 'filter'}filter{else}read{/if}{if \$pager.limit}/limit/{\$pager.limit}{/if}">
{if \$pager.prev >= 1}
        <span id="link"><a href="{\$uri}{$name}/{if \$_action == 'filter'}filter{else}read{/if}{if \$pager.limit > 0}/limit/{\$pager.limit}{/if}/page/{\$pager.prev}{if \$vars}?{\$vars}{/if}">&lt;</a>&nbsp;</span>
{/if}
        <span>{\$page} {if \$pager.total_pages > 0}<input type="text" name="page" value="{\$pager.current}" /> {\$of} {\$pager.total_pages}{else}1 {\$of} 1{/if}</span>
{if \$pager.next >= 1}
        <span id="link">&nbsp;<a href="{\$uri}{$name}/{if \$_action == 'filter'}filter{else}read{/if}{if \$pager.limit > 0}/limit/{\$pager.limit}{/if}/page/{\$pager.next}{if \$vars}?{\$vars}{/if}">&gt;</a></span>
{/if}
      </form>
    </td>
  </tr>
  <tr>
    <td class="data">
      <table>
        <tr>

DOT;

$cols = '';
foreach ( $columns as $key => $column ) {
  $cols .= "          <th>{\${$key}}</th>\n";
}
$cols .= "        </tr>\n" .
         "{foreach from=\$list item=item}\n" .
         "        <tr>\n";
foreach ( $columns as $key => $column ) {
  switch ( $column->column->type ) {
    case 'boolean' :
      $f = "          <td><a href=\"{\$uri}{$name}/{\$item.id}\">{if \$item.{$key}}{\$true}{else}{\$false}{/if}</a></td>\n";
      break;
    default :
      $f = "          <td><a href=\"{\$uri}{$name}/{\$item.id}/read\">{\$item.{$key}}</a></td>\n";
  }
  $cols .= $f;
  unset( $f );
}

$str .= $cols .= <<<DOT
        </tr>
{/foreach}
     </table>
   </td>
 </tr>
{else}
  <tr>
    <td class="title">
      <div id="title">{\$title}</div>
      <div align="right">
        <a href="{\$uri}">{\$home}</a> &nbsp;
        <a href="{\$uri}{$name}">{\$back}</a> &nbsp;
        <a href="{\$uri}{$name}/{\$_id}/edit">{\$edit}</a> &nbsp;
        <a href="{\$uri}{$name}/{\$_id}/delete">{\$delete}</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="data">

DOT;

$cols = '';
foreach ( $columns as $key => $column ) {
  $cols .= "      <div class=\"element\">\n" .
           "        <label>{\${$key}}</label>\n";
  switch ( $column->column->type ) {
    case 'boolean' :
      $f = "        <span>{if \$element.{$key}}{\$true}{else}{\$false}{/if}</span>\n";
      break;
    default :
      $f = "        <span>{\$element.{$key}}</span>\n";
  }
  $cols .= $f;
  unset( $f );
  $cols .= "      </div>\n\n";
}

$str .= $cols .= <<<DOT
    </td>
  </tr>
{/if}
{elseif \$_action == 'add'}
  <tr>
    <td class="title">
      <div id="title">{\$title}</div>
      <div align="right">
        <a href="{\$uri}">{\$home}</a> &nbsp;
        <a href="javascript:history.back();">{\$back}</a> &nbsp;
      </div>
    </td>
  </tr>
  <tr>
    <td class="data">
     <form method="post" action="{\$uri}{$name}">

DOT;

$cols = '';
foreach ( $columns as $key => $column ) {
  $cols .= "        <div class=\"element\">\n" .
           "          <label for=\"{$key}\">{\${$key}}</label>\n";
  switch ( $column->column->type ) {
    case 'longvarchar' :
      $f = "          <textarea name=\"{$key}\"></textarea>\n";
      break;
    case 'boolean' :
      $f  = "          {\$true}<input type=\"radio\" name=\"{$key}\" value=\"true\"/>\n" .
            "          {\$false}<input type=\"radio\" name=\"{$key}\" value=\"false\"/>\n";
      break;
    default :
      $f = '';
      // looks for references
      if ( isset( $column->reference ) && $column->reference ) {
        $f .= "          <select name=\"{$key}\">\n" .
              "            <option value=\"\">Select an option</option>\n" .
              "{foreach from=\${$column->reference->table} item=item}\n" .
              "            <option value=\"{\$item.id}\">{\$item.id}</option>\n" .
              "{/foreach}\n".
              "          </select>\n";
      } else {
        $f .= "          <input type=\"text\" name=\"{$key}\" />\n";
      }
  }
  $cols .= $f;
  unset( $f );
  $cols .= "        </div>\n\n";
}

$str .= $cols .= <<<DOT
        <div class="element">
          <label>&nbsp;</label>
          <input type="submit" value="{\$save}" />
        </div>
      </form>
    </td>
  </tr>
{elseif \$_action == 'edit'}
  <tr>
    <td class="title">
      <div id="title">{\$title}</div>
      <div align="right">
        <a href="{\$uri}">{\$home}</a> &nbsp;
        <a href="javascript:history.back();">{\$back}</a> &nbsp;
      </div>
    </td>
  </tr>
  <tr>
    <td class="data">
      <form method="post" action="{\$uri}{$name}/{\$_id}">

DOT;

$cols = '';
foreach ( $columns as $key => $column ) {
  $cols .= "        <div class=\"element\">\n" .
           "          <label for=\"{$key}\">{\${$key}}</label>\n";
  switch ( $column->column->type ) {
    case 'longvarchar' :
      $f = "          <textarea name=\"{$key}\">{\$element.{$key}}</textarea>\n";
      break;
    case 'boolean' :
      $f  = "          {\$true}<input type=\"radio\" name=\"{$key}\" value=\"true\"{if \$element.{$key}} checked{/if}/>\n" .
            "          {\$false}<input type=\"radio\" name=\"{$key}\" value=\"false\"{if !\$element.{$key}} checked{/if}/>\n";
      break;
    default :
      $f = '';
      // looks for references
      if ( isset( $column->reference ) && $column->reference ) {
        $f .= "          <select name=\"{$key}\">\n".
              "            <option value=\"\">Select an option</option>\n" .
              "{foreach from=\${$column->reference->table} item=item}\n" .
              "            <option value=\"{\$item.id}\"{if \$element.{$key} == \$item.id}selected{/if}>{\$item.id}</option>\n" .
              "{/foreach}\n" .
              "          </select>\n";
      } else {
        $f = "          <input type=\"text\" name=\"{$key}\" value=\"{\$element.{$key}}\" />\n";
      }
  }
  $cols .= $f; unset( $f );
  $cols .= "        </div>\n\n";
}

$str .= $cols .= <<<DOT
        <div class="element">
          <label>&nbsp;</label>
          <input type="submit" value="{\$save}" />
        </div>
     </form>
    </td>
  </tr>
{/if}
{/if}
      </table>
    </td>
  </tr>
</table>
{include file='foot.tpl'}
DOT;
