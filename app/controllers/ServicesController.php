<?php
class ServicesController {
    public function index() {
        require_once '../app/models/Service.php';
        $services = Service::getAll();
        include '../app/views/layouts/header.php';
        include '../app/views/services/services_list.php';
        include '../app/views/layouts/footer.php';
    }

    public function services() {
        require_once '../app/models/Service.php';
        if (isset($_GET['delete_id'])) {
            Service::delete((int)$_GET['delete_id']);
            header('Location: /public/index.php?page=admin_services');
            exit;
        }
        include '../app/views/admin/services.php';
    }
} 