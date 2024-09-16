<?php

class Media {

    public $imageInfo;
    public $fileName;
    public $fileType;
    public $fileTempPath;

    public $userPath = './uploads';
    public $upload_extensions = array('gif', 'jpg', 'jpeg', 'png');

    public function file_ext($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $this->upload_extensions);
    }

    public function upload($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Erro ao fazer upload do arquivo: ' . $file['error']);
        }

        if (!isset($file['tmp_name']) || empty($file['tmp_name']) || !file_exists($file['tmp_name'])) {
            throw new RuntimeException('Caminho temporário do arquivo está vazio ou não existe.');
        }

        $this->imageInfo = getimagesize($file['tmp_name']);
        if ($this->imageInfo === false) {
            throw new RuntimeException('Falha ao obter informações da imagem.');
        }

        $this->fileName  = basename($file['name']);
        $this->fileType  = $this->imageInfo['mime'];
        $this->fileTempPath = $file['tmp_name'];
        return true;
    }

    public function process_user($id) {
        $ext = pathinfo($this->fileName, PATHINFO_EXTENSION);
        $new_name = $this->randString(8) . $id . '.' . $ext;
        $this->fileName = $new_name;

        if ($this->user_image_destroy($id)) {
            if (move_uploaded_file($this->fileTempPath, $this->userPath . '/' . $this->fileName)) {
                if ($this->update_userImg($id)) {
                    unset($this->fileTempPath);
                    return true;
                }
            }
        }
        return false;
    }

    public function randString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $charactersLength - 1);
            $randomString .= $characters[$randomIndex];
        }

        return $randomString;
    }

    private function update_userImg($id) {
        global $db;
        $sql = "UPDATE user SET image='{$this->fileName}' WHERE id='{$id}'";
        $result = $db->query($sql);
        return ($result && $db->affected_rows === 1);
    }

    public function user_image_destroy($id) {
        $image = $this->find('user', $id);
        if ($image && $image['image'] !== 'no_image.jpg') {
            unlink($this->userPath . '/' . $image['image']);
        }
        return true;
    }

    public function find($table, $id) {
        global $db;
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $db->query($sql);
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : false;
    }
}
?>
