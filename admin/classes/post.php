<?php

use helpers\Format;

include_once __DIR__ . '/../../lib/database.php';
include_once __DIR__ . '/../../helpers/format.php';

?>

<?php

class post
{
    private $db;
    // format
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_post($catName, $catDescription)
    {
        $catName = $this->fm->validation($catName);
        $catDescription = $this->fm->validation($catDescription);

        $catName = mysqli_real_escape_string($this->db->link, $catName);
        $catDescription = mysqli_real_escape_string($this->db->link, $catDescription);
        if (!empty($catName)) {
            $query = "INSERT INTO tbl_category(catName, catDescription) VALUES('$catName', '$catDescription')";
            $result = $this->db->insert($query);
        }
    }

    public function show_post()
    {
        $query = "SELECT * FROM tbl_category ORDER BY catID DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_post_one($id)
    {
        $query = "SELECT * FROM tbl_category WHERE catID = $id";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_post($id, $catName, $catDescription)
    {
        $query = "update tbl_category set catName = '$catName', catDescription = '$catDescription' where catID = $id ";
        $result = $this->db->update($query);
        return $result;
    }
    public function delete_post($id){
        $query = "delete from tbl_category where catID = $id";
        $result = $this->db->delete($query);
        return $result;
    }
}

?>