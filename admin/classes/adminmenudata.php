<?php

use helpers\Format;

include __DIR__ . '/../../lib/database.php';
include __DIR__ . '/../../helpers/format.php';

class adminmenudata
{
    private $db;
    // format
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function menu_admin($adminUser, $adminPass)
    {
        $adminUser = $this->fm->validation($adminUser);
        $adminPass = $this->fm->validation($adminPass);

        $adminUser = mysqli_real_escape_string($this->db->link, $adminUser);
        $adminPass = mysqli_real_escape_string($this->db->link, $adminPass);

        if (empty($adminUser) || empty($adminPass)) {
            $alert = "Tên người dùng và mật khẩu không được để trống";
            return $alert;
        } else {
            $query = "SELECT * FROM tbl_admin WHERE adminUser = '$adminUser' AND adminPass = '$adminPass' LIMIT 1";
            $result = $this->db->select($query);
            if ($result != false) {
                $value = $result->fetch_assoc();
                Session::set('adminlogin', true);
                Session::set('adminID', $value['adminID']);
                Session::set('adminUser', $value['adminUser']);
                Session::set('adminName', $value['adminName']);
                Session::set('Avatar', $value['Avatar']);
                header('Location:index.php');
            } else {
                $alert = "Tài khoản hoặc mật khẩu không đúng";
                return $alert;
            }
        }

    }

    public function insert_admin_menu($adminMenuName, $ParentLevel, $MenuOrder, $MenuTarget, $Icon, $Link, $IdName, $IsActive, $ClassName)
    {
        $adminMenuName = $this->fm->validation($adminMenuName);
        $ParentLevel = $this->fm->validation($ParentLevel);
        $MenuOrder = $this->fm->validation($MenuOrder);
        $MenuTarget = $this->fm->validation($MenuTarget);
        $Icon = $this->fm->validation($Icon);
        $Link = $this->fm->validation($Link);
        $IdName = $this->fm->validation($IdName);
        $IsActive = $this->fm->validation($IsActive);

        $adminMenuName = mysqli_real_escape_string($this->db->link, $adminMenuName);
        $ParentLevel = mysqli_real_escape_string($this->db->link, $ParentLevel);
        $MenuOrder = mysqli_real_escape_string($this->db->link, $MenuOrder);
        $MenuTarget = mysqli_real_escape_string($this->db->link, $MenuTarget);
        $Icon = mysqli_real_escape_string($this->db->link, $Icon);
        $Link = mysqli_real_escape_string($this->db->link, $Link);
        $IdName = mysqli_real_escape_string($this->db->link, $IdName);
        $IsActive = mysqli_real_escape_string($this->db->link, $IsActive);
        $ClassName = mysqli_real_escape_string($this->db->link, $ClassName);
        if (!empty($adminMenuName)) {
            $query = "INSERT INTO tbl_adminmenu(adminMenuName, ParentLevel, MenuOrder, MenuTarget, Icon, Link, IdName, IsActive, ClassName) VALUES('$adminMenuName', $ParentLevel, $MenuOrder, '$MenuTarget', '$Icon', '$Link', '$IdName', $IsActive, '$ClassName')";
            $result = $this->db->insert($query);
        }
    }

    public function show_admin_menu()
    {
        $query = "SELECT * FROM tbl_adminmenu ORDER BY adminMenuID DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_menu_by_role()
    {
        $id = Session::get('roleID');
        $query = "select * from tbl_adminmenu where adminMenuID in (select tbl_adminmenurole.adminMenuID from tbl_adminmenurole where roleID=$id) and IsActive = true order by ParentLevel asc, MenuOrder asc";
        $result = $this->db->select($query);
        $list = [];
        while ($record = $result->fetch_assoc()) {
            $list[] = array(
                'adminMenuName' => $record['adminMenuName'],
                'adminMenuID' => $record['adminMenuID'],
                'ParentLevel' => $record['ParentLevel'],
                'MenuOrder' => $record['MenuOrder'],
                'MenuTarget' => $record['MenuTarget'],
                'Icon' => $record['Icon'],
                'Link' => $record['Link'],
                'IdName' => $record['IdName'],
                'ClassName' => $record['ClassName'],
                'Childrens' => []
            );
        }

        for ($i = count($list) - 1; $i >= 0; $i--) {
            if ($list[$i]['ParentLevel'] != null) {
                $item = $list[$i];
                $cha = array_search($item['ParentLevel'], array_column($list, 'adminMenuID'));
                array_unshift($list[$cha]['Childrens'], $item);
//                $a = json_encode($cha);
//                echo "<script>console.log('$a')</script>";
            }
        }

        foreach ($list as $key => $item) {
            if ($item['ParentLevel'] != null) unset($list[$key]);
        }

        return $list;
    }

    public function show_admin_menu_one($id)
    {
        $query = "SELECT * FROM tbl_adminmenu WHERE adminMenuID = $id";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_admin_menu($id, $adminMenuName, $MenuLevel, $ParentLevel, $MenuOrder, $MenuTarget, $Icon, $Link, $IdName, $IsActive)
    {
        $query = "update tbl_adminmenu set adminMenuName = '$adminMenuName', ParentLevel = '$ParentLevel', MenuOrder = '$MenuOrder', MenuTarget = '$MenuTarget', Icon = '$Icon', Link = '$Link', IdName = '$IdName', IsActive = '$IsActive' where adminMenuID = $id ";
        $result = $this->db->update($query);
        return $result;
    }

    public function delete_admin_menu($id)
    {
        $query = "delete from tbl_adminmenu where adminMenuID = $id";
        $result = $this->db->delete($query);
        return $result;
    }
}

?>