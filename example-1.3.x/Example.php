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
 * Descritor do módulo Exemplo do SNEP
 *
 * @category  Example
 * @package   Example
 * @copyright Copyright (c) 2011 OpenS Tecnologia
 * @author    Rafael Pereira Bozzetti <rafael@opens.com.br>
 */

class Example extends Snep_Module_Descriptor {
    
    public function __construct() {

        // Define informações do Módulo
        $this->setName("Example Module");
        $this->setVersion("1.0");
        $this->setDescription("Module example of SNEP");
        $this->setModuleId('example');

        // Define os menus da aplicação.
        $menu_item = array(
            new Snep_Menu_Item('Snep_Example', "Consulta no Banco", "example/teste/database/"),
            new Snep_Menu_Item('Snep_Example', "Arquivo de Configuração", "example/teste/config/"),
            new Snep_Menu_Item('Snep_Example', "Formulários", "example/teste/forms/"),
            new Snep_Menu_Item('Snep_Example', "Cadastro de Itens", "example/cadastro/index/")
        );

        // Define item de menu e sub itens do menu.
        $menu = new Snep_Menu_Item('example', 'Example Module', null, $menu_item);

        $this->setMenuTree(array($menu));
        
        // Define o caminho para as classes do módulo no include_path
        set_include_path( get_include_path() .
                          PATH_SEPARATOR .
                          Zend_Registry::get("config")->system->path->base .
                          "/modules/example/lib");

        // Define o namespace para as classes do módulo.
        Zend_Loader_Autoloader::getInstance()->registerNamespace("Example_");
        
        // Captura instancia do Zend_Controller_Front
        $front = Zend_Controller_Front::getInstance();
        $plugin = $front->getPlugin("AclPlugin");

        /* Definição dos resources do módulo, não funcional na série 1.3
         * 
        if($plugin instanceof AclPlugin) {
            $acl = $plugin->getAcl();
            $acl->addResource("agents");
            $acl->allow(null, "agents");
        }        
        */        
    }
}

