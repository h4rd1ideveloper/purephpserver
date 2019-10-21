<?php
<object class="entiboletao" name="entiboletao" baseclass="Page">
  <property name="Background"></property>
  <property name="DocType">dtNone</property>
  <property name="Height">370</property>
  <property name="IsMaster">0</property>
  <property name="Name">entiboletao</property>
  <property name="Width">915</property>
  <property name="OnBeforeShow">entiboletaoBeforeShow</property>
  <object class="HiddenField" name="hf_titulo" >
    <property name="Height">18</property>
    <property name="Left">395</property>
    <property name="Name">hf_titulo</property>
    <property name="Width">200</property>
  </object>
  <object class="Label" name="Label1" >
    <property name="Caption">Titulos</property>
    <property name="Height">22</property>
    <property name="Left">10</property>
    <property name="Name">Label1</property>
    <property name="Style">lb-titulo</property>
    <property name="Width">283</property>
  </object>
  <object class="Shape" name="Shape1" >
    <property name="Brush">
    <property name="Color">#FFFFFF</property>
    </property>
    <property name="Height">1</property>
    <property name="Left">10</property>
    <property name="Name">Shape1</property>
    <property name="Pen">
    <property name="Color">#E0E0E0</property>
    </property>
    <property name="Top">31</property>
    <property name="Width">900</property>
  </object>
  <object class="DBRepeater" name="DBRepeater1" >
    <property name="DataSource"></property>
    <property name="Height">30</property>
    <property name="Layout">
    <property name="Type">XY_LAYOUT</property>
    </property>
    <property name="Left">10</property>
    <property name="Name">DBRepeater1</property>
    <property name="ParentColor">0</property>
    <property name="Style">dbrp</property>
    <property name="Top">63</property>
    <property name="Width">900</property>
    <object class="Label" name="Label3" >
      <property name="Alignment">agCenter</property>
      <property name="DataField">CONTRATO</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Name">Label3</property>
      <property name="Top">9</property>
      <property name="Width">120</property>
    </object>
    <object class="Label" name="Label5" >
      <property name="Height">13</property>
      <property name="Left">126</property>
      <property name="Name">Label5</property>
      <property name="Top">9</property>
      <property name="Width">150</property>
      <property name="OnBeforeShow">Label5BeforeShow</property>
    </object>
    <object class="Label" name="Label9" >
      <property name="Caption">Label3</property>
      <property name="DataField">DESCRICAO</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">283</property>
      <property name="Name">Label9</property>
      <property name="Top">9</property>
      <property name="Width">503</property>
    </object>
    <object class="Label" name="Label14" >
      <property name="Alignment">agRight</property>
      <property name="Caption">Label3</property>
      <property name="Height">13</property>
      <property name="Left">795</property>
      <property name="Name">Label14</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
      <property name="OnBeforeShow">Label14BeforeShow</property>
    </object>
  </object>
  <object class="Label" name="Label2" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Contrato</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label2</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">41</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label4" >
    <property name="Caption">Status</property>
    <property name="Height">18</property>
    <property name="Left">136</property>
    <property name="Name">Label4</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">41</property>
    <property name="Width">60</property>
  </object>
  <object class="Label" name="Label8" >
    <property name="Caption">Motivo</property>
    <property name="Height">18</property>
    <property name="Left">293</property>
    <property name="Name">Label8</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">41</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label12" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Valor</property>
    <property name="Height">18</property>
    <property name="Left">810</property>
    <property name="Name">Label12</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">41</property>
    <property name="Width">100</property>
  </object>
  <object class="Button" name="Button3" >
    <property name="Caption"><![CDATA[Reemitir Bolet&atilde;o]]></property>
    <property name="Height">30</property>
    <property name="Left">664</property>
    <property name="Name">Button3</property>
    <property name="Style">bt-azul</property>
    <property name="Top">1</property>
    <property name="Width">120</property>
    <property name="jsOnClick">Button3JSClick</property>
  </object>
  <object class="HiddenField" name="HiddenField1" >
    <property name="Height">18</property>
    <property name="Left">360</property>
    <property name="Name">HiddenField1</property>
    <property name="Top">10</property>
    <property name="Width">200</property>
  </object>
  <object class="Label" name="Label6" >
    <property name="Caption">Total:</property>
    <property name="Height">18</property>
    <property name="Left">793</property>
    <property name="Name">Label6</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">10</property>
    <property name="Width">43</property>
  </object>
  <object class="Database" name="database" >
        <property name="Left">590</property>
        <property name="Top">-20</property>
    <property name="Connected"></property>
    <property name="DriverName">mysql</property>
    <property name="Name">database</property>
  </object>
  <object class="StyleSheet" name="StyleSheet1" >
        <property name="Left">730</property>
        <property name="Top">120</property>
    <property name="FileName">libs/css/estilo.css</property>
    <property name="Name">StyleSheet1</property>
  </object>
  <object class="Query" name="qrpesquisa" >
        <property name="Left">650</property>
        <property name="Top">0</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrpesquisa</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Datasource" name="dspesquisa" >
        <property name="Left">540</property>
        <property name="Top">-10</property>
    <property name="DataSet">qrpesquisa</property>
    <property name="Name">dspesquisa</property>
  </object>
  <object class="Label" name="Label7" >
    <property name="Alignment">agRight</property>
    <property name="Height">18</property>
    <property name="Left">837</property>
    <property name="Name">Label7</property>
    <property name="Style">lb-control</property>
    <property name="Top">10</property>
    <property name="Width">75</property>
  </object>
</object>
?>
