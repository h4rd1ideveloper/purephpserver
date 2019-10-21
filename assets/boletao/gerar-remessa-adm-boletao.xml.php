<?php
<object class="gerarremessaadmboletao" name="gerarremessaadmboletao" baseclass="Page">
  <property name="Background"></property>
  <property name="Caption">gerarremessadm</property>
  <property name="DocType">dtNone</property>
  <property name="Height">515</property>
  <property name="IsMaster">0</property>
  <property name="Name">gerarremessaadmboletao</property>
  <property name="Width">915</property>
  <property name="OnBeforeShow">gerarremessaadmboletaoBeforeShow</property>
  <property name="jsOnLoad">gerarremessaadmboletaoJSLoad</property>
  <object class="Label" name="Label8" >
    <property name="Caption"><![CDATA[Selecione o Condom&iacute;nio]]></property>
    <property name="Height">18</property>
    <property name="Left">488</property>
    <property name="Name">Label8</property>
    <property name="Style">lb-control</property>
    <property name="Width">300</property>
  </object>
  <object class="ComboBox" name="ComboBox2" >
    <property name="Height">30</property>
    <property name="Items"><![CDATA[a:1:{i:-1;s:5:&quot;GERAL&quot;;}]]></property>
    <property name="Left">488</property>
    <property name="Name">ComboBox2</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">2</property>
    <property name="Top">22</property>
    <property name="Width">422</property>
  </object>
  <object class="HiddenField" name="hf_titulo" >
    <property name="Height">18</property>
    <property name="Left">711</property>
    <property name="Name">hf_titulo</property>
    <property name="Value"><![CDATA[Bolet&atilde;o]]></property>
    <property name="Width">200</property>
  </object>
  <object class="Label" name="Label1" >
    <property name="Caption">Selecione o Averbador</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label1</property>
    <property name="Style">lb-control</property>
    <property name="Width">323</property>
  </object>
  <object class="ComboBox" name="ComboBox1" >
    <property name="Height">30</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">10</property>
    <property name="Name">ComboBox1</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">1</property>
    <property name="Top">22</property>
    <property name="Width">469</property>
    <property name="OnBeforeShow">ComboBox1BeforeShow</property>
    <property name="jsOnChange">ComboBox1JSChange</property>
  </object>
  <object class="Button" name="Button1" >
    <property name="Caption">Atualizar Contratos</property>
    <property name="Height">30</property>
    <property name="Left">621</property>
    <property name="Name">Button1</property>
    <property name="Style">bt-azul</property>
    <property name="TabOrder">11</property>
    <property name="Top">144</property>
    <property name="Width">130</property>
    <property name="OnClick">Button1Click</property>
    <property name="jsOnClick">Button1JSClick</property>
  </object>
  <object class="Label" name="Label2" >
    <property name="Caption">Contratos encontrados</property>
    <property name="Height">22</property>
    <property name="Left">10</property>
    <property name="Name">Label2</property>
    <property name="Style">lb-titulo</property>
    <property name="Top">183</property>
    <property name="Width">275</property>
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
    <property name="Top">214</property>
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
    <property name="Top">246</property>
    <property name="Width">900</property>
    <object class="Label" name="Label4" >
      <property name="Caption">Label4</property>
      <property name="DataField">CPFCNPJ</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">260</property>
      <property name="Name">Label4</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
    </object>
    <object class="Label" name="Label6" >
      <property name="Caption">Label4</property>
      <property name="DataField">NOME</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">369</property>
      <property name="Name">Label6</property>
      <property name="Top">8</property>
      <property name="Width">209</property>
    </object>
    <object class="Label" name="Label9" >
      <property name="Alignment">agCenter</property>
      <property name="Caption">Label4</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">692</property>
      <property name="Name">Label9</property>
      <property name="Top">9</property>
      <property name="Width">80</property>
      <property name="OnBeforeShow">Label9BeforeShow</property>
    </object>
    <object class="Label" name="Label11" >
      <property name="Alignment">agRight</property>
      <property name="Caption">Label4</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Left">781</property>
      <property name="Name">Label11</property>
      <property name="Top">9</property>
      <property name="Width">80</property>
      <property name="OnBeforeShow">Label11BeforeShow</property>
    </object>
    <object class="Label" name="Label13" >
      <property name="Autosize">1</property>
      <property name="Caption"><![CDATA[&lt;i class=&quot;fa fa-trash-alt
&quot;&gt;&lt;/i&gt;]]></property>
      <property name="Height">23</property>
      <property name="Hint">Excluir</property>
      <property name="Left">868</property>
      <property name="Name">Label13</property>
      <property name="ParentColor">0</property>
      <property name="ParentShowHint">0</property>
      <property name="ShowHint">1</property>
      <property name="Style">bt_icone vermelho btexcluir</property>
      <property name="Top">3</property>
      <property name="Width">25</property>
      <property name="OnBeforeShow">Label13BeforeShow</property>
    </object>
    <object class="Label" name="lb_contrato" >
      <property name="Alignment">agCenter</property>
      <property name="Caption">jairoba</property>
      <property name="Height">13</property>
      <property name="Left">584</property>
      <property name="Name">lb_contrato</property>
      <property name="Top">9</property>
      <property name="Width">100</property>
      <property name="OnBeforeShow">lb_contratoBeforeShow</property>
    </object>
    <object class="Label" name="Label19" >
      <property name="Caption">Label4</property>
      <property name="DataField">EMPREGADOR</property>
      <property name="DataSource">dspesquisa</property>
      <property name="Height">13</property>
      <property name="Name">Label19</property>
      <property name="Top">8</property>
      <property name="Width">243</property>
    </object>
  </object>
  <object class="Label" name="Label3" >
    <property name="Caption">CPF/CNPJ</property>
    <property name="Height">18</property>
    <property name="Left">270</property>
    <property name="Name">Label3</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label5" >
    <property name="Caption">Nome</property>
    <property name="Height">18</property>
    <property name="Left">379</property>
    <property name="Name">Label5</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label7" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Contrato</property>
    <property name="Height">18</property>
    <property name="Left">594</property>
    <property name="Name">Label7</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label10" >
    <property name="Caption">Vencimento</property>
    <property name="Height">18</property>
    <property name="Left">702</property>
    <property name="Name">Label10</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label12" >
    <property name="Alignment">agCenter</property>
    <property name="Caption">Valor</property>
    <property name="Height">18</property>
    <property name="Left">791</property>
    <property name="Name">Label12</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label14" >
    <property name="Caption"><![CDATA[Per&iacute;odo]]></property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label14</property>
    <property name="Style">lb-control</property>
    <property name="Top">122</property>
    <property name="Width">75</property>
  </object>
  <object class="Button" name="Button2" >
    <property name="Caption">Filtrar</property>
    <property name="Height">30</property>
    <property name="Left">295</property>
    <property name="Name">Button2</property>
    <property name="ParentColor">0</property>
    <property name="Style">bt-azul</property>
    <property name="TabOrder">8</property>
    <property name="Top">144</property>
    <property name="Width">75</property>
    <property name="OnClick">Button2Click</property>
    <property name="jsOnClick">Button2JSClick</property>
  </object>
  <object class="Edit" name="Edit1" >
    <property name="Height">30</property>
    <property name="Left">40</property>
    <property name="Name">Edit1</property>
    <property name="Style">form-control data calendario</property>
    <property name="TabOrder">6</property>
    <property name="Top">144</property>
    <property name="Width">100</property>
  </object>
  <object class="Edit" name="Edit2" >
    <property name="Height">30</property>
    <property name="Left">185</property>
    <property name="Name">Edit2</property>
    <property name="Style">form-control data calendario</property>
    <property name="TabOrder">7</property>
    <property name="Top">144</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label15" >
    <property name="Alignment">agRight</property>
    <property name="Caption">Total</property>
    <property name="Height">18</property>
    <property name="Left">702</property>
    <property name="Name">Label15</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">194</property>
    <property name="Width">80</property>
  </object>
  <object class="Label" name="Label16" >
    <property name="Alignment">agRight</property>
    <property name="Caption">0,00</property>
    <property name="Height">18</property>
    <property name="Left">791</property>
    <property name="Name">Label16</property>
    <property name="Style">lb-control</property>
    <property name="Top">194</property>
    <property name="Width">80</property>
  </object>
  <object class="Button" name="Button3" >
    <property name="Caption">Gerar Remessa</property>
    <property name="Height">30</property>
    <property name="Left">458</property>
    <property name="Name">Button3</property>
    <property name="Style">bt-azul</property>
    <property name="Top">49</property>
    <property name="Visible">0</property>
    <property name="Width">159</property>
    <property name="OnClick">Button3Click</property>
  </object>
  <object class="Edit" name="Edit3" >
    <property name="Height">30</property>
    <property name="Left">379</property>
    <property name="Name">Edit3</property>
    <property name="Style">form-control data calendario</property>
    <property name="TabOrder">9</property>
    <property name="Top">144</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label17" >
    <property name="Caption">Venc. Boleto</property>
    <property name="Height">18</property>
    <property name="Left">379</property>
    <property name="Name">Label17</property>
    <property name="Style">lb-control</property>
    <property name="Top">122</property>
    <property name="Width">100</property>
  </object>
  <object class="HiddenField" name="HiddenField1" >
    <property name="Height">18</property>
    <property name="Left">574</property>
    <property name="Name">HiddenField1</property>
    <property name="Top">122</property>
    <property name="Width">200</property>
  </object>
  <object class="Button" name="Button5" >
    <property name="Caption"><![CDATA[Gerar Bolet&atilde;o]]></property>
    <property name="Height">30</property>
    <property name="Left">760</property>
    <property name="Name">Button5</property>
    <property name="Style">bt-azul</property>
    <property name="TabOrder">11</property>
    <property name="Top">144</property>
    <property name="Width">150</property>
    <property name="jsOnClick">Button5JSClick</property>
  </object>
  <object class="HiddenField" name="hf_relatorio" >
    <property name="Height">18</property>
    <property name="Left">54</property>
    <property name="Name">hf_relatorio</property>
    <property name="Top">122</property>
    <property name="Width">200</property>
  </object>
  <object class="Label" name="Label18" >
    <property name="Caption"><![CDATA[Condom&iacute;nio]]></property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label18</property>
    <property name="Style">lb-control titulo-tabela</property>
    <property name="Top">224</property>
    <property name="Width">100</property>
  </object>
  <object class="Label" name="Label20" >
    <property name="Caption">De:</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label20</property>
    <property name="Style">lb-control</property>
    <property name="Top">150</property>
    <property name="Width">26</property>
  </object>
  <object class="Label" name="Label21" >
    <property name="Caption"><![CDATA[At&eacute;:]]></property>
    <property name="Height">18</property>
    <property name="Left">149</property>
    <property name="Name">Label21</property>
    <property name="Style">lb-control</property>
    <property name="Top">150</property>
    <property name="Width">33</property>
  </object>
  <object class="Button" name="Button4" >
    <property name="Caption">Visualizar</property>
    <property name="Height">30</property>
    <property name="Left">790</property>
    <property name="Name">Button4</property>
    <property name="Style">bt-azul</property>
    <property name="Top">55</property>
    <property name="Visible">0</property>
    <property name="Width">120</property>
    <property name="OnClick">Button4Click</property>
  </object>
  <object class="Edit" name="Edit4" >
    <property name="Height">30</property>
    <property name="Left">169</property>
    <property name="MaxLength"></property>
    <property name="Name">Edit4</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">4</property>
    <property name="Top">83</property>
    <property name="Width">443</property>
  </object>
  <object class="Label" name="Label22" >
    <property name="Caption">Informe os Dados para Pesquisa do Cliente</property>
    <property name="Height">18</property>
    <property name="Left">169</property>
    <property name="Name">Label22</property>
    <property name="Style">lb-control</property>
    <property name="Top">61</property>
    <property name="Width">417</property>
  </object>
  <object class="Label" name="Label23" >
    <property name="Caption">Tipo de Pesquisa</property>
    <property name="Height">18</property>
    <property name="Left">10</property>
    <property name="Name">Label23</property>
    <property name="Style">lb-control</property>
    <property name="Top">61</property>
    <property name="Width">150</property>
  </object>
  <object class="ComboBox" name="ComboBox3" >
    <property name="Height">30</property>
    <property name="Items"><![CDATA[a:2:{i:1;s:8:&quot;CPF/CNPJ&quot;;i:2;s:4:&quot;NOME&quot;;}]]></property>
    <property name="Left">10</property>
    <property name="Name">ComboBox3</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">3</property>
    <property name="Top">83</property>
    <property name="Width">150</property>
  </object>
  <object class="ComboBox" name="ComboBox4" >
    <property name="Height">30</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">621</property>
    <property name="Name">ComboBox4</property>
    <property name="Style">form-control</property>
    <property name="TabOrder">5</property>
    <property name="Top">83</property>
    <property name="Width">290</property>
    <property name="OnBeforeShow">ComboBox4BeforeShow</property>
  </object>
  <object class="Label" name="Label24" >
    <property name="Caption"><![CDATA[Situa&ccedil;&atilde;o da Pessoa]]></property>
    <property name="Height">18</property>
    <property name="Left">621</property>
    <property name="Name">Label24</property>
    <property name="Style">lb-control</property>
    <property name="Top">61</property>
    <property name="Width">204</property>
  </object>
  <object class="Label" name="Label25" >
    <property name="Autosize">1</property>
    <property name="Caption"><![CDATA[&lt;i class=&quot;fa fa-exclamation-circle&quot;&gt;&lt;/i&gt;]]></property>
    <property name="Height">23</property>
    <property name="Hint"><![CDATA[Parcelas Fora do Bolet&atilde;o]]></property>
    <property name="Left">878</property>
    <property name="Name">Label25</property>
    <property name="ParentColor">0</property>
    <property name="ParentShowHint">0</property>
    <property name="ShowHint">1</property>
    <property name="Style">bt_icone verde popup</property>
    <property name="Top">221</property>
    <property name="Width">25</property>
    <property name="OnBeforeShow">Label25BeforeShow</property>
  </object>
  <object class="Button" name="Button6" >
    <property name="Caption"><![CDATA[Relat&oacute;rio PDF]]></property>
    <property name="Height">30</property>
    <property name="Left">488</property>
    <property name="Name">Button6</property>
    <property name="ParentColor">0</property>
    <property name="Style">bt-azul</property>
    <property name="TabOrder">10</property>
    <property name="Top">144</property>
    <property name="Width">124</property>
    <property name="OnClick">Button6Click</property>
    <property name="jsOnClick">Button6JSClick</property>
  </object>
  <object class="HiddenField" name="HiddenField2" >
    <property name="Height">18</property>
    <property name="Left">693</property>
    <property name="Name">HiddenField2</property>
    <property name="Top">384</property>
    <property name="Width">200</property>
  </object>
  <object class="Database" name="database" >
        <property name="Left">361</property>
        <property name="Top">341</property>
    <property name="Connected"></property>
    <property name="DriverName">mysql</property>
    <property name="Name">database</property>
  </object>
  <object class="StyleSheet" name="StyleSheet1" >
        <property name="Left">40</property>
        <property name="Top">342</property>
    <property name="FileName">libs/css/estilo.css</property>
    <property name="Name">StyleSheet1</property>
  </object>
  <object class="Query" name="qrinserir" >
        <property name="Left">315</property>
        <property name="Top">342</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrinserir</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qrproposta" >
        <property name="Left">525</property>
        <property name="Top">342</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrproposta</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qraverbador" >
        <property name="Left">415</property>
        <property name="Top">342</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qraverbador</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Datasource" name="dspesquisa" >
        <property name="Left">470</property>
        <property name="Top">342</property>
    <property name="DataSet">qrpesquisa</property>
    <property name="Name">dspesquisa</property>
  </object>
  <object class="Query" name="qrpesquisa" >
        <property name="Left">210</property>
        <property name="Top">341</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrpesquisa</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qrboletao" >
        <property name="Left">95</property>
        <property name="Top">341</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrboletao</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qratualizar" >
        <property name="Left">270</property>
        <property name="Top">341</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qratualizar</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
  <object class="Query" name="qrempresa" >
        <property name="Left">150</property>
        <property name="Top">341</property>
    <property name="Database">database</property>
    <property name="LimitCount">-1</property>
    <property name="LimitStart">-1</property>
    <property name="Name">qrempresa</property>
    <property name="Params">a:0:{}</property>
    <property name="SQL">a:0:{}</property>
  </object>
</object>
?>
