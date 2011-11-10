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

class Example_CadastroController extends Zend_Controller_Action {

    /**
     * Método index, listagem padrão de itens.
     */
    public function indexAction() {

        // Define a baseUrl para a rotina, utilizado nos links para demais rotinas na view
        $this->view->url = $this->getRequest()->getModuleName() .'/'. $this->getRequest()->getControllerName();

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Inicio");

        // Consulta no banco de dados
        $db = Zend_Registry::get('db');

        // Cria objeto do tipo Zend_Db_Select e realiza consulta.
        $select = $db->select()
                ->from('example');

        $stmt = $db->query($select);
        $examples = $stmt->fetchAll();

        // Envia dados para view.
        $this->view->example = $examples;

        // Define links na barra de filtros.
        $this->view->filter = array(array("url" => "{$this->getFrontController()->getBaseUrl()}/{$this->getRequest()->getModuleName()}/{$this->getRequest()->getControllerName()}/add/",
                                          "display" => $this->view->translate("Novo Item"),
                                          "css" => "include"));


    }

    /**
     * Método add, adicionar item.
     */
    public function addAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Cadastro");

        // Parse do arquivo de formulário.
        $xml = new Zend_Config_Xml( Zend_Registry::get("config")->system->path->base .
                                              "/modules/example/forms/cadastro.xml" );
        // Cria objeto Snep_Form
        $form = new Snep_Form( $xml);
        
        // setButton é um método da classe Snep_Form que inclue o menu padrão de botões.
        $form->setButton();

        // Verifica se existe dados sendo enviados via $_POST
        // Se for verdadeiro, é porque o formulário foi submetido.
        if ($this->_request->getPost()) {

            // Chama método isValid() e confronta os dados submetidos pelo formulário.
            $isValid = $form->isValid($_POST);

            // Caso tudo seja válido chama a classe (Model) para inserir o registro.
            if( $isValid ) {

                // Chama método estático para adicionar o registro.
                Example_Manager::add($_POST);

                // Após inserir dados redireciona para método index
                $this->_redirect( $this->getRequest()->getModuleName() .'/'. $this->getRequest()->getControllerName() );
            }
         }

        // Envia form para a view
        $this->view->form = $form;

    }
    
    /**
     * Método remove,remove um item conforme id fornecido.
     */
    public function removeAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Remover");

        // Pega o id do item selecionado, passado via GET
        $id = $this->_request->getParam("id");

        if($id) {
            // Chama método para remover item do banco.
            Example_Manager::remove($id);
        }
        // Após remover ou nao dados redireciona para método index
        $this->_redirect( $this->getRequest()->getModuleName() .'/'. $this->getRequest()->getControllerName() );

    }

    /**
     * Método edit, editar item conforme id fornecido.
     */
    public function editAction() {

        // Define o título da página, é mostrado automaticamente na view.
        $this->view->breadcrumb = $this->view->translate("Exemplo » Editar");

        // Pega o id do item selecionado, passado via GET
        $id = $this->_request->getParam("id");

        // Chama método Example_Cadastro::get(), ele retorna os dados confome o id.
        $dados = Example_Manager::get($id);

        // Parse do arquivo formulário.
        $xml = new Zend_Config_Xml( Zend_Registry::get("config")->system->path->base .
                                              "/modules/example/forms/cadastro.xml" );
        // Cria objeto Snep_Form
        $form = new Snep_Form( $xml);

        // Preenche dados do formulário com dados vindos do banco.
        // Perceba que ele captura o elemento e ao mesmo tempo seta um valor para ele.
        $form->getElement('id')->setValue($dados['id']);
        $form->getElement('nome')->setValue($dados['nome']);

        // Chama método que insere a barra padrão.
        $form->setButton();

        // Verifica se existe dados sendo enviados via $_POST
        // Se for verdadeiro, é porqyue o formulário foi submetido.
        if ($this->_request->getPost()) {

            // Chama método isValid() é confronta os dados submetidos pelo formulário.
            $isValid = $form->isValid($_POST);

            // Caso tudo seja válido chama a classe (Model) para inserir o dado.
            if( $isValid ) {
                
                // Chama método estático para atualizar o registro.
                Example_Manager::update($_POST);
                
                // Após remover ou nao dados redireciona para método index
                $this->_redirect( $this->getRequest()->getModuleName() .'/'. $this->getRequest()->getControllerName() );
            }
         }

        // Envia form para a view
        $this->view->form = $form;

    }

}
