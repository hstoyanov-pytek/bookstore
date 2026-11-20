<?php

namespace App\Admin\Controller;

class AdminController extends BaseAdminController
{
    public function __construct(\App\Repository\BookRepository $repository)
    {
        parent::__construct($repository);
    }

    private function validateUploadedImage() : string
    {
        var_dump($_FILES);
        if(empty($_FILES) && empty($_FILES['cover']))
        {
            return "";
        }
        $file = $_FILES['cover'];
        var_dump(is_readable($file['tmp_name']));

        if(!is_readable($file['tmp_name'])) return "";

        $imageName = "";

        if($file['error'] === 0 && $file['size'] !== 0)
        {
            $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
            $name = preg_replace('/[^a-zA-Z0-9]/', "", $fileName);

            $originalImage = $file['tmp_name'];
            $imageName = $name . "-" . time() . ".jpg";

            $destImage = __DIR__ . "/../../../uploads/{$imageName}";
            $imageSize = getimagesize($originalImage);
            if(!empty($imageSize))
            {
                [$width, $height] = $imageSize;
                $maxDimensions = 400;
                $scaleFactor = $maxDimensions / max($width, $height);
                $newWidth = $width * $scaleFactor;
                $newHeight = $height * $scaleFactor;

                $img = imagecreatefromstring(file_get_contents($originalImage));
                if(!empty($img))
                {
                    $newImg = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagejpeg($newImg, $destImage);
                }
            }
        }

        return $imageName;
    }

    public function displayAdminDashboard()
    {
        $page = empty($_GET['page']) ? 1 : @(int)($_GET['page']);

        $results = $this->repository->fetchByPage($page, RESULTS_DISPLAYED_ON_PAGE);

        $this->render("pages/index", [
            'page' => $page,
            'results' => $results,
        ]);
    }

    public function deleteBookEntryById() : void
    {
        $id = @(int)($_POST['id']);
        if(!empty($id))
        {
            $this->repository->switchAvailabilityState($id);
        }

        header("Location: /admin");
    }

    public function createBookEntry() : void
    {
        if(!empty($_POST))
        {
            $entryArray = array();
            $imageName = $this->validateUploadedImage();

            foreach ($_POST as $key => $var)
            {
                $entryArray[$key] = @(string)$var;
            }
            $entryArray['cover_name'] = $imageName;

            $this->repository->createEntry($entryArray);
            header("Location: /admin");
        }

        $authors = $this->repository->fetchAllAuthors();
        $genres = $this->repository->fetchAllGenres();
        $languages = $this->repository->fetchAllLanguages();

        $this->displayPage('createBook', [
            'authors' => $authors,
            'genres' => $genres,
            'languages' => $languages
        ]);
    }

    public function editBookEntry() : void
    {
        $id = @(int)($_GET['book_id'] ?? 1);
        if(!empty($_POST))
        {
            $imageName = $this->validateUploadedImage();

            $values = array();
            foreach ($_POST as $name => $var)
            {
                $values[$name] = @(string)($var);
            }
            if(empty($_POST['available']))
            {
                $values['available'] = false;
            }
            $values['cover'] = $imageName;

            $details = new BookDetailsDTO();
            $details->arrayToObject($values);
            $details->book_id = $id;
            $this->repository->updateById($id, $details);
            header("Location: /admin");
        }

        $result = $this->repository->fetchBookById($id);
        $authors = $this->repository->fetchAllAuthors();
        $genres = $this->repository->fetchAllGenres();
        $languages = $this->repository->fetchAllLanguages();
        $publishers = $this->repository->fetchAllPublishers();

        if(empty($result))
        {
            echo "No books found with id: {$id}";
            return;
        }

        $this->displayPage("editBook", [
            'result' => $result,
            'authors' => $authors,
            'genres' => $genres,
            'languages' => $languages,
            'publishers' => $publishers
        ]);
    }


}