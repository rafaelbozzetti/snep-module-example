<?php
/**
 *  This file is part of SNEP.
 *  Para território Brasileiro leia LICENCA_BR.txt
 *  All other countries read the following disclaimer
 *
 *  SNEP is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as
 *  published by the Free Software Foundation, either version 3 of
 *  the License, or (at your option) any later version.
 *
 *  SNEP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with SNEP.  If not, see <http://www.gnu.org/licenses/lgpl.txt>.
 */

/**
 * Class to manager example on database
 *
 * @see Example
 *
 * @category  Snep
 * @package   Example
 * @copyright Copyright (c) 2011 OpenS Tecnologia
 * @author    Rafael Pereira Bozzetti <rafael@opens.com.br>
 *
 */
class Example_Manager {

    /**
     * Consulta e retorna dados do elemento no bancom conforme id.
     * @param <int> $id
     * @return <array>
     */
    public static function get($id) {

        $db = Zend_Registry::get('db');

        $select = $db->select()
                        ->from('example')
                        ->where("id = ?", $id);

        $stmt = $db->query($select);
        $examples = $stmt->fetch();

        return $examples;        
    }

    /**
     * Adiciona um elemento no banco.
     * @param <array> $data
     */
    public static function add($data) {

        $db = Zend_Registry::get('db');

        $insert_data = array("nome" => $data['nome']);

        $db->beginTransaction();

        try{
            $db->insert('example', $insert_data);
            $db->commit();
            
        }catch(Exception $e){
            $db->rollback();

        }
        
    }

    /**
     * Remove um elemento do banco conforme id
     * @param <int> $id
     */
    public static function remove($id) {

        $db = Zend_Registry::get('db');

        $db->beginTransaction();

        try {
            $db->delete('example', "id = $id");
            $db->commit();
            
        } catch (Exception $e) {
            $db->rollBack();

        }

    }

    /**
     * Atualiza as informações de um elemento conforme dados passados.
     * @param <array> $data
     */
    public static function update($data) {

        $db = Zend_Registry::get('db');

        $update_data = array("nome" => $data['name']);

        $db->beginTransaction();

        try {
            $db->update("example", $update_data, "id = '{$data['id']}'");
            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();

        }
    }

}