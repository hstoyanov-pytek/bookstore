<?php

namespace App\Admin\Controller;
use \App\Model\BookDetailsDTO;
use \App\Utilities\StaticHelpers;
class AdminController extends BaseAdminController
{
    public function __construct(
                               private \App\Repository\UsersRepository $userRepository,
                               \App\Repository\BookRepository $bookRepository,
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

    public function displayAllAuthors() : void
    {
        $page = @(int)($_GET['page']) ?? 1;
        $author = @(string)($_GET['author']) ?? "";
        if(!empty($author))
        {
            $results = $this->bookRepository->fetchBooksByAuthor($author);

            $this->render("../templates/authorsTable", [
                'page' => $page,
                'author' => $author,
                'results' => $results
            ]);
        }

        parent::displayAllAuthors();
    }

    public function displayAllGenres() : void
    {
        $page = @($_GET['page']) ?? 1;
        $genre = @(string)($_GET['genre']) ?? "";

        if(!empty($genre))
        {
            $this->displayBooksByGenre($genre, $page, RESULTS_DISPLAYED_ON_PAGE);
        }

        parent::displayAllGenres();
    }

    public function displayBooksByGenre(string $genre, int $page, int $limit) : void
    {
        $entriesCount = $this->bookRepository->getAvailableBooksByGenre($genre);
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref'=> "genres?" . http_build_query(['genre' => $genre ,'page' => max(1, $page - 1)]),
            'nextHref' => "genres?" . http_build_query(['genre' => $genre,'page' => min($page + 1, $totalPages)])
        ]);
        $results = $this->bookRepository->fetchBooksByGenre($genre);

        $this->render("../templates/genresTable",array_merge(
            $paginationArray,
            array(
                'genre' => $genre,
                'results' => $results
            )
        ));
    }

    public function displayAllLanguages() : void
    {
        $page = @(int)($_GET['page']) ?? 1;
        $language = @(string)($_GET['language']) ?? "";

        if(!empty($language))
        {
            $results = $this->bookRepository->fetchBooksByLanguage($language);

            $this->render("../templates/languagesTable",[
                'page' => $page,
                'language' => $language,
                'results' => $results
            ]);
        }

        parent::displayAllLanguages();
    }

    public function displayAllPublishers() : void
    {
        $page = @(int)($_GET['page']) ?? 1;
        $publisher = @(string)($_GET['publisher']) ?? "";

        if(!empty($publisher))
        {
            $results = $this->bookRepository->fetchBooksByPublisher($publisher);

            $this->render("../templates/publishersTable",[
                'page' => $page,
                'publisher' => $publisher,
                'results' => $results
            ]);
        }

        parent::displayAllPublishers();
    }

    public function displayAdminDashboard() : void
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

        $lastPage = @$_POST['page'] ?? "";
        $author = @$_POST['author'] ?? "";
        if(!empty($lastPage) && !empty($author))
        {
            header("location: /admin/authors?" . http_build_query(["author" => $author]));
            return;
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

            $this->bookRepository->createEntry($entryArray);
            header("Location: /admin");
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

            /// TODO:: Come up with a solution for the header, to redirect to whatever page we came from if we are
            /// editing from the navigation
            header("Location: /admin");
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

    public function displayAllUsers() : void
    {
        $results = $this->userRepository->fetchAllUsers();

        $this->displayNavigation("users", [
            'results' => $results
        ]);
    }
    public function deleteUser() : void
    {
        if(!empty($_POST))
        {
            $id = @(int)($_POST['id']);
            if(!empty($id))
            {
                $this->userRepository->deleteUserById($id);

                header("Location: /admin/users");
                die();
            }
        }

        $results = $this->userRepository->fetchAllUsers();

        $this->displayNavigation("users", [
            'results' => $results
        ]);
    }
    public function editUser() : void
    {
        if(!empty($_POST))
        {
            $details = new \App\Model\UserDetailsDTO();
            $details->arrayToObject($_POST);

            $this->userRepository->updateUser($details);

            header("Location: /admin/users");
        }

        $id = @(int)($_GET['id']);
        if(empty($id))
        {
            header("Location: /admin/users");
            die();
        }

        $user = $this->userRepository->getUserById($id);
        $roles = $this->userRepository->getRoles();

        parent::render("pages/editUser", [
            'user' => $user,
            'roles' => $roles
        ]);
    }
}