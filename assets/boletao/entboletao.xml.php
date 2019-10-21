<?php
<object class="entboletao" name="entboletao" baseclass="Page">
  <property name="Background"></property>
  <property name="DocType">dtNone</property>
  <property name="Height">371</property>
  <property name="IsMaster">0</property>
  <property name="Name">entboletao</property>
  <property name="Width">915</property>
  <property name="OnBeforeShow">entboletaoBeforeShow</property>
  <property name="jsOnLoad">entboletaoJSLoad</property>
  <object class="HiddenField" name="hf_titulo" >
    <property name="Height">18</property>
    <property name="Left">161</property>
    <property name="Name">hf_titulo</property>
    <property name="Top">223</property>
    <property name="Value"><![CDATA[Bolet&otilde;es Gerados]]></property>
    <property name="Width">200</property>
  </object>
  <object class="Label" name="Label1" >
    <property name="Caption">Gerados</property>
    <property name="Height">22</property>
    <property name="Left">10</property>
    <property name="Name">Label1</property>
    <property name="Style">lb-titulo</property>
    <property name="Top">58</property>
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
    <property name="Top">89</property>
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
    <property name="Style">dbrp</property>
    <property name="Top">121</property>
    <property name="Width">900</property>
    <object class="Label" name="Label3" >
      <property name="Alignment">agCenter</property>
      <property name="Caption">Label3</property>
      <property name="DataField">CODBOLETAO</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Name">Label3</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
    </object>
    <object class="Label" name="Label5" >
      <property name="Alignment">agCenter</property>
      <property name="Caption">Label3</property>
      <property name="Height">13</property>
      <property name="Left">109</property>
      <property name="Name">Label5</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
      <property name="OnBeforeShow">Label5BeforeShow</property>
    </object>
    <object class="Label" name="Label7" >
      <property name="Caption">Label7</property>
      <property name="Height">13</property>
      <property name="Left">595</property>
      <property name="Name">Label7</property>
      <property name="Top">9</property>
      <property name="Width">150</property>
      <property name="OnBeforeShow">Label7BeforeShow</property>
    </object>
    <object class="Label" name="Label9" >
      <property name="Alignment">agCenter</property>
      <property name="Caption">Label3</property>
      <property name="Height">13</property>
      <property name="Left">218</property>
      <property name="Name">Label9</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
      <property name="OnBeforeShow">Label9BeforeShow</property>
    </object>
    <object class="Label" name="Label11" >
      <property name="Alignment">agRight</property>
      <property name="Caption">Label3</property>
      <property name="Height">13</property>
      <property name="Left">754</property>
      <property name="Name">Label11</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
      <property name="OnBeforeShow">Label11BeforeShow</property>
    </object>
    <object class="Label" name="Label13" >
      <property name="Autosize">1</property>
      <property name="Caption"><![CDATA[&lt;i class=&quot;fa fa-pencil&quot;&gt;&lt;/i&gt;]]></property>
      <property name="Height">23</property>
      <property name="Hint">Verificar</property>
      <property name="Left">870</property>
      <property name="Name">Label13</property>
      <property name="ParentColor">0</property>
      <property name="ParentShowHint">0</property>
      <property name="ShowHint">1</property>
      <property name="Style">bt_editar</property>
      <property name="Top">3</property>
      <property name="Width">25</property>
      <property name="OnBeforeShow">Label13BeforeShow</property>
    </object>
    <object class="Label" name="Label14" >
      <property name="Caption">Label3</property>
      <property name="DataField">ADM</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">327</property>
      <property name="Name">Label14</property>
      <property name="Top">9</property>
      <property name="Width">259</property>
    </object>
  </object>
  <object class="Label" name="Label2" >
    <property name="Alignment">agCenter</property>
    <property name="Caption"><![CDATA[C&oacute;digo]]></property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label2</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label4" >
    <property name="Alignment">agCenter</property>
    <property name="Caption"><![CDATA[Data Gera&ccedil;&atilde;o]]></property>
    <property name="Height">18</property>
    <property name="Left">119</property>
    <property name="Name">Label4</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label6" >
    <property name="Caption"><![CDATA[Usu&aacute;rio]]></property>
    <property name="Height">18</property>
    <property name="Left">605</property>
    <property name="Name">Label6</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">150</property>
  </object>
  <object class="Label" name="Label8" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Data Venc.</property>
    <property name="Height">18</property>
    <property name="Left">228</property>
    <property name="Name">Label8</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label10" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Valor</property>
    <property name="Height">18</property>
    <property name="Left">764</property>
    <property name="Name">Label10</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">100</property>
  </object>
  <object class="Database" name="database" >
        <property name="Left">440</property>
        <property name="Top">233</property>
    <property name="Connected"></property>
    <property name="DriverName">mysql</property>
    <property name="Name">database</property>
  </object>
  <object class="StyleSheet" name="StyleSheet1" >
        <property name="Left">485</property>
        <property name="Top">258</property>
    <property name="FileName">libs/css/estilo.css</property>
    <property name="Name">StyleSheet1</property>
  </object>
  <object class="Query" name="qrpesquisa" >
        <property name="Left">545</property>
        <property name="Top">233</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrpesquisa</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Datasource" name="dspesquisa" >
        <property name="Left">415</property>
        <property name="Top">308</property>
    <property name="DataSet">qrpesquisa</property>
    <property name="Name">dspesquisa</property>
  </object>
  <object class="Label" name="Label12" >
    <property name="Caption">Administradora</property>
    <property name="Height">18</property>
    <property name="Left">337</property>
    <property name="Name">Label12</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">99</property>
    <property name="Width">133</property>
  </object>
  <object class="Label" name="Label15" >
    <property name="Caption"><![CDATA[Per&iacute;odo]]></property>
    <property name="Height">18</property>
    <property name="Left">568</property>
    <property name="Name">Label15</property>
    <property name="Style">lb-control</property>
    <property name="Width">75</property>
  </object>
  <object class="Label" name="Label20" >
    <property name="Caption">De:</property>
    <property name="Height">18</property>
    <property name="Left">568</property>
    <property name="Name">Label20</property>
    <property name="Style">lb-control</property>
    <property name="Top">28</property>
    <property name="Width">26</property>
  </object>
  <object class="Edit" name="Edit1" >
    <property name="Height">30</property>
    <property name="Left">598</property>
    <property name="Name">Edit1</property>
    <property name="Style">form-control data calendario</property>
    <property name="TabOrder">3</property>
    <property name="Top">22</property>
    <property name="Width">95</property>
  </object>
  <object class="Label" name="Label21" >
    <property name="Caption"><![CDATA[At&eacute;:]]></property>
    <property name="Height">18</property>
    <property name="Left">697</property>
    <property name="Name">Label21</property>
    <property name="Style">lb-control</property>
    <property name="Top">28</property>
    <property name="Width">33</property>
  </object>
  <object class="Edit" name="Edit2" >
    <property name="Height">30</property>
    <property name="Left">731</property>
    <property name="Name">Edit2</property>
    <property name="Style">form-control data calendario</property>
    <property name="TabOrder">4</property>
    <property name="Top">22</property>
    <property name="Width">95</property>
  </object>
  <object class="Button" name="Button2" >
    <property name="Caption">Filtrar</property>
    <property name="Height">30</property>
    <property name="Left">835</property>
    <property name="Name">Button2</property>
    <property name="ParentColor">0</property>
    <property name="Style">bt-azul</property>
    <property name="TabOrder">5</property>
    <property name="Top">22</property>
    <property name="Width">75</property>
    <property name="OnClick">Button2Click</property>
  </object>
  <object class="ComboBox" name="ComboBox1" >
    <property name="Height">30</property>
    <property name="Items"><![CDATA[a:1:{s:0:&quot;&quot;;s:0:&quot;&quot;;}]]></property>
    <property name="Left">10</property>
    <property name="Name">ComboBox1</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">1</property>
    <property name="Top">22</property>
    <property name="Width">272</property>
    <property name="OnBeforeShow">ComboBox1BeforeShow</property>
    <property name="jsOnChange">ComboBox1JSChange</property>
  </object>
  <object class="Label" name="Label16" >
    <property name="Caption">Selecione o Averbador</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label16</property>
    <property name="Style">lb-control</property>
    <property name="Width">272</property>
  </object>
  <object class="ComboBox" name="ComboBox2" >
    <property name="Height">30</property>
    <property name="Items"><![CDATA[a:1:{i:-1;s:5:&quot;GERAL&quot;;}]]></property>
    <property name="Left">291</property>
    <property name="Name">ComboBox2</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">2</property>
    <property name="Top">22</property>
    <property name="Width">268</property>
  </object>
  <object class="Label" name="Label17" >
    <property name="Caption"><![CDATA[Selecione o Condom&iacute;nio]]></property>
    <property name="Height">18</property>
    <property name="Left">291</property>
    <property name="Name">Label17</property>
    <property name="Style">lb-control</property>
    <property name="Width">268</property>
  </object>
  <object class="HiddenField" name="HiddenField2" >
    <property name="Height">18</property>
    <property name="Left">179</property>
    <property name="Name">HiddenField2</property>
    <property name="Top">262</property>
    <property name="Width">200</property>
  </object>
  <object class="Query" name="qraverbador" >
        <property name="Left">625</property>
        <property name="Top">232</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qraverbador</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
</object>
?>
