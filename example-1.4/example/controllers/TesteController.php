<?php
/**
 *  This file is part of SNEP.
 *
 *  SNEP is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  SNEP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with SNEP.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Classe de controlador exemplo
 *
 * @category  Example
 * @package   Example
 * @copyright Copyright (c) 2011 OpenS Tecnologia
 * @author    Rafael Pereira Bozzetti <rafael@opens.com.br>
 */

class Example_TesteController extends Zend_Controller_Action {

    /**
     * Método index, Não está sendo utilizado, neste caso. 
     */
    public function indexAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Inicio");

    }

    /**
     * Método database, faz uma listagem simples do banco de dados.
     */
    public function databaseAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Consulta no Banco de Dados");

        // Recupera instancia do Banco de dados no Zend_Registry
        $db = Zend_Registry::get('db');

        // Cria um objeto select
        $select = $db->select()

                     // Tabela do select e indices que deverão ser retornados.
                     ->from('cdr', array('calldate', 'src', 'dst'))

                     // Método Where, estabelece critérios pra consulta.
                     ->where('disposition = ?', 'ANSWERED')

                     // Método Limit, limita o número de registros.
                     ->limit('50');

        // Cria método de consulta.
        $stmt = $db->query($select);

        // Chama método fetchAll();
        $calls = $stmt->fetchAll();

        // Envia o resultado para a view
        $this->view->calls = $calls;
    }

    /**
     * Método config, pega dados do arquivo de configuração do SNEP includes/setup.conf.
     */
    public function configAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Arquivo de Configuração");

        // Recupera instancia do objeto config no Zend_Registry e pega parametro do arquivo.
        $config = Zend_Registry::get('config');
        $empresa = $config->ambiente->emp_nome;

        // Envia o objeto para a View
        $this->view->empresa = $empresa;

    }

    public function formsAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Formulários");

        // Parse do arquivo formulário.
        $xml = new Zend_Config_Xml( Zend_Registry::get("config")->system->path->base .
                                              "/modules/example/forms/example.xml" );
        // Cria objeto Snep_Form
        $form = new Snep_Form( $xml);

        // Pega determinado elemento do Form
        $campo4 = $form->getElement('campo04');

        // Seta as opções do elemento dinamicamente.
        $campo4->setMultiOptions( array(1 => 'teste1', 2 => 'teste2', 3 => 'teste3') );

        // Chama método setButton(), que inclue a barra de botões padrão do SNEP.
        $form->setButton();
                
        // Verifica se existe dados sendo enviados via $_POST
        // Se for verdadeiro, é porqyue o formulário foi submetido.
        if ($this->_request->getPost()) {

            // Chama método isValid() é confronta os dados submetidos pelo formulário.
            $isValid = $form->isValid($_POST);

            if( $isValid ) {
                $this->view->message = $this->view->translate("Dados recebidos") ."!";
                $this->view->dados = $_POST;
            }
         }

        // Envia o objeto Snep_Form para a View
        $this->view->form = $form;
    }

}
