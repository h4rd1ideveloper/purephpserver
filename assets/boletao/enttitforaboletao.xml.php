<?php
<object class="enttitforaboletao" name="enttitforaboletao" baseclass="Page">
  <property name="Background"></property>
  <property name="Caption">enttitforaboletao</property>
  <property name="DocType">dtNone</property>
  <property name="Height">396</property>
  <property name="IsMaster">0</property>
  <property name="Name">enttitforaboletao</property>
  <property name="Width">510</property>
  <property name="OnBeforeShow">enttitforaboletaoBeforeShow</property>
  <object class="HiddenField" name="HiddenField1" >
    <property name="Height">18</property>
    <property name="Left">24</property>
    <property name="Name">HiddenField1</property>
    <property name="Top">265</property>
    <property name="Width">200</property>
  </object>
  <object class="HiddenField" name="hf_titulo" >
    <property name="Height">18</property>
    <property name="Left">52</property>
    <property name="Name">hf_titulo</property>
    <property name="Top">229</property>
    <property name="Value"><![CDATA[Parcelas Fora do Bolet&atilde;o no Per&iacute;odo]]></property>
    <property name="Width">200</property>
  </object>
  <object class="Shape" name="Shape2" >
    <property name="Brush">
    <property name="Color">#dfdfdf</property>
    </property>
    <property name="Height">1</property>
    <property name="Left">10</property>
    <property name="Name">Shape2</property>
    <property name="Pen">
    <property name="Color">#dfdfdf</property>
    </property>
    <property name="Top">32</property>
    <property name="Width">500</property>
  </object>
  <object class="Label" name="Label3" >
    <property name="Caption"><![CDATA[Parcelas Fora do Bolet&atilde;o no Per&iacute;odo]]></property>
    <property name="Font">
    <property name="Color">#606060</property>
    <property name="Size">13px</property>
    </property>
    <property name="Height">13</property>
    <property name="Left">10</property>
    <property name="Name">Label3</property>
    <property name="ParentFont">0</property>
    <property name="Style">lb-titulo</property>
    <property name="Width">403</property>
  </object>
  <object class="Label" name="Label2" >
    <property name="Caption">CPF/CNPJ</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label2</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">64</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label5" >
    <property name="Caption">Nome</property>
    <property name="Height">18</property>
    <property name="Left">152</property>
    <property name="Name">Label5</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">64</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label7" >
    <property name="Caption">Contrato</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label7</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">86</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label10" >
    <property name="Caption">Vencimento</property>
    <property name="Height">18</property>
    <property name="Left">152</property>
    <property name="Name">Label10</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">86</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label12" >
    <property name="Alignment">agRight</property>
    <property name="Caption">Valor</property>
    <property name="Height">18</property>
    <property name="Left">241</property>
    <property name="Name">Label12</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">86</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label18" >
    <property name="Caption"><![CDATA[Condom&iacute;nio]]></property>
    <property name="Height">18</property>
    <property name="Left">152</property>
    <property name="Name">Label18</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">42</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label15" >
    <property name="Alignment">agRight</property>
    <property name="Caption">Total</property>
    <property name="Height">18</property>
    <property name="Left">330</property>
    <property name="Name">Label15</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">86</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label16" >
    <property name="Alignment">agRight</property>
    <property name="Caption">0,00</property>
    <property name="Height">18</property>
    <property name="Left">418</property>
    <property name="Name">Label16</property>
    <property name="Style">lb-control</property>
    <property name="Top">86</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label1" >
    <property name="Caption"><![CDATA[Situa&ccedil;&atilde;o]]></property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label1</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">42</property>
    <property name="Width">100</property>
  </object>
  <object class="DBRepeater" name="DBRepeater1" >
    <property name="DataSource"></property>
    <property name="Height">77</property>
    <property name="Layout">
    <property name="Type">XY_LAYOUT</property>
    </property>
    <property name="Left">10</property>
    <property name="Name">DBRepeater1</property>
    <property name="ParentColor">0</property>
    <property name="Style">dbrp</property>
    <property name="Top">113</property>
    <property name="Width">495</property>
    <object class="Label" name="Label4" >
      <property name="Caption">Label4</property>
      <property name="DataField">CPFCNPJ</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Name">Label4</property>
      <property name="Top">32</property>
      <property name="Width">133</property>
    </object>
    <object class="Label" name="lb_contrato" >
      <property name="Caption">jairoba</property>
      <property name="Height">14</property>
      <property name="Name">lb_contrato</property>
      <property name="Top">54</property>
      <property name="Width">133</property>
      <property name="OnBeforeShow">lb_contratoBeforeShow</property>
    </object>
    <object class="Label" name="Label9" >
      <property name="Caption">Label4</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">14</property>
      <property name="Left">142</property>
      <property name="Name">Label9</property>
      <property name="Top">54</property>
      <property name="Width">80</property>
      <property name="OnBeforeShow">Label9BeforeShow</property>
    </object>
    <object class="Label" name="Label6" >
      <property name="Caption">Label4</property>
      <property name="DataField">NOME</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">142</property>
      <property name="Name">Label6</property>
      <property name="Top">32</property>
      <property name="Width">311</property>
    </object>
    <object class="Label" name="Label19" >
      <property name="Caption">Label4</property>
      <property name="DataField">EMPREGADOR</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">142</property>
      <property name="Name">Label19</property>
      <property name="Top">10</property>
      <property name="Width">311</property>
    </object>
    <object class="Label" name="Label11" >
      <property name="Alignment">agRight</property>
      <property name="Caption">Label4</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">14</property>
      <property name="Left">231</property>
      <property name="Name">Label11</property>
      <property name="Top">54</property>
      <property name="Width">80</property>
      <property name="OnBeforeShow">Label11BeforeShow</property>
    </object>
    <object class="Label" name="Label13" >
      <property name="Autosize">1</property>
      <property name="Caption"><![CDATA[&lt;i class=&quot;fa fa-check-circle&quot;&gt;&lt;/i&gt;]]></property>
      <property name="Height">23</property>
      <property name="Hint">Incluir Novamente</property>
      <property name="Left">460</property>
      <property name="Name">Label13</property>
      <property name="ParentColor">0</property>
      <property name="ParentShowHint">0</property>
      <property name="ShowHint">1</property>
      <property name="Style">bt_icone verde btincluir</property>
      <property name="Top">27</property>
      <property name="Width">25</property>
      <property name="OnBeforeShow">Label13BeforeShow</property>
    </object>
    <object class="Label" name="Label8" >
      <property name="Caption">Label4</property>
      <property name="DataField">SITUACAO</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Name">Label8</property>
      <property name="Top">10</property>
      <property name="Width">133</property>
      <property name="OnBeforeShow">Label8BeforeShow</property>
    </object>
  </object>
  <object class="StyleSheet" name="StyleSheet1" >
        <property name="Left">312</property>
        <property name="Top">250</property>
    <property name="FileName">libs/css/estilo.css</property>
    <property name="Name">StyleSheet1</property>
  </object>
  <object class="Query" name="qrpesquisa" >
        <property name="Left">362</property>
        <property name="Top">220</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrpesquisa</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qroperacoes" >
        <property name="Left">188</property>
        <property name="Top">291</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qroperacoes</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qrhistorico" >
        <property name="Left">217</property>
        <property name="Top">209</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrhistorico</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Datasource" name="dshistorico" >
        <property name="Left">276</property>
        <property name="Top">221</property>
    <property name="DataSet">qrhistorico</property>
    <property name="Name">dshistorico</property>
  </object>
  <object class="Database" name="database" >
        <property name="Left">210</property>
        <property name="Top">231</property>
    <property name="Connected"></property>
    <property name="DriverName">mysql</property>
    <property name="Name">database</property>
  </object>
  <object class="Datasource" name="dspesquisa" >
        <property name="Left">286</property>
        <property name="Top">301</property>
    <property name="DataSet">qrpesquisa</property>
    <property name="Name">dspesquisa</property>
  </object>
</object>
?>
