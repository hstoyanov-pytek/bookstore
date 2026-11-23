<?php

namespace App\Editor\Controller;
use \App\Utilities\AuthService;

class EditorController extends BaseEditorController
{
    public function __construct(\App\Repository\BookRepository $bookRepository,
                                \App\Utilities\AuthService $authService)
    {
        parent::__construct($bookRepository, $authService);
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

    public function displayEditorDashboard()
    {
        $page = empty($_GET['page']) ? 1 : @(int)($_GET['page']);

        $results = $this->bookRepository->fetchByPage($page, RESULTS_DISPLAYED_ON_PAGE);

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
            $this->bookRepository->switchAvailabilityState($id);
        }

        header("Location: /editor");
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

            $this->bookRepository->createEntry($entryArray);
            header("Location: /editor");
        }

        $authors = $this->bookRepository->fetchAllAuthors();
        $genres = $this->bookRepository->fetchAllGenres();
        $languages = $this->bookRepository->fetchAllLanguages();

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
            $this->bookRepository->updateById($id, $details);
            header("Location: /editor");
        }

        $result = $this->bookRepository->fetchBookById($id);
        $authors = $this->bookRepository->fetchAllAuthors();
        $genres = $this->bookRepository->fetchAllGenres();
        $languages = $this->bookRepository->fetchAllLanguages();
        $publishers = $this->bookRepository->fetchAllPublishers();

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