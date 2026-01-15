<?php

    interface Record {

        //  implement Create logic
        public function create();

        // retrieving all records from a table
        public function find_all();

        //  retrieving a single record by its primary key
        public function find_by_id($id);

        // updating an existing record
        public function update();

        //  deleting an existing record
        public function delete();

    }

?>
