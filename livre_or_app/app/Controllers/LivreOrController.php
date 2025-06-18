<?php

namespace App\Controllers;

use App\Models\LivreOrModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivreOrController extends ResourceController
{
    protected $modelName = 'App\Models\LivreOrModel';
    protected $format = 'json';

    public function index()
    {
        // $livre_or_cards = $this->model->findAll();
        // return view('LivreOrView/galerieView', ['cards' => $livre_or_cards]);

        $livre_or_cards = $this->model->paginate(2);
        $pager = $this->model->pager;

        return view('LivreOrView/galerieView', [
            'cards' => $livre_or_cards,
            'pager' => $pager
        ]);
    }

    // Create a new entry in the Livre d'Or
    public function create(){
        helper(['form', 'url']);
        $rules = [
            'livre_or_name' => [
                'label' => 'Nom et Prénoms',
                'rules' => 'required|string|max_length[255]',
                'errors' => [
                    'required' => 'Le champ {field} est obligatoire.',
                    'max_length' => 'Le champ {field} ne doit pas dépasser 255 caractères.'
                ]
            ],
            'livre_or_club_name' => [
                'label' => 'Nom du Club',
                'rules' => 'required|string|max_length[255]',
                'errors' => [
                    'required' => 'Le champ {field} est obligatoire.',
                    'max_length' => 'Le champ {field} ne doit pas dépasser 255 caractères.'
                ]
            ],
            'livre_or_message' => [
                'label' => 'Message',
                'rules' => 'required|min_length[10]|max_length[500]',
                'errors' => [
                    'required' => 'Le champ {field} est obligatoire.',
                    'min_length' => 'Le champ {field} doit contenir au moins 10 caractères.',
                    'max_length' => 'Le champ {field} ne doit pas dépasser 500 caractères.'
                ]
            ],
            'livre_or_image' => [
                'label' => 'Image',
                'rules' => 'permit_empty|uploaded[livre_or_image]|max_size[livre_or_image,1024]|is_image[livre_or_image]|ext_in[livre_or_image,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => 'Vous devez sélectionner une image.',
                    'max_size' => 'L\'image ne doit pas dépasser 1 Mo.',
                    'is_image' => 'Le fichier doit être une image.',
                    'ext_in' => 'L\'image doit être au format jpg, jpeg ou png.'
                ]
            ],
            'livre_or_city' => [
                'label' => 'Ville',
                'rules' => 'required|string|max_length[255]',
                'errors' => [
                    'max_length' => 'Le champ {field} ne doit pas dépasser 255 caractères.'
                ]
            ]
        ];
        // Validate the data
        if (!$this->validate($rules)) {
            $livre_or_cards = $this->model->findAll();
            return view('LivreOrView/galerieView', [
                'cards' => $livre_or_cards,
                'validation' => $this->validator
            ]);
        }
        // Get the data from the form
        $file = $this->request->getFile('livre_or_image');
        $data = [
            'livre_or_name' => ucwords(strtolower($this->request->getPost('livre_or_name'))),
            'livre_or_club_name' => ucwords(strtolower($this->request->getPost('livre_or_club_name'))),
            'livre_or_message' => $this->request->getPost('livre_or_message'),
            'livre_or_image' => $this->request->getPost('livre_or_image'),
            'livre_or_city' => ucwords(strtolower($this->request->getPost('livre_or_city'))),
        ];

        // If there an image uploaded
        if (!$file || !$file->isValid()) {
            $data['livre_or_image'] = null; // No image uploaded
            $livre_or_id = $this->model->insert($data);
            $data['livre_or_id'] = $livre_or_id;
        } else {
            $module = 'livre_or_images';
            $extension = $file->getExtension();
            $filePath = 'uploads/' . $module . '/';
            $newName = 'livre_or_' . $data['livre_or_name'] . '.' . $extension;

            if (!$file->hasMoved()){
                $file->move(WRITEPATH. $filePath, $newName);
            }
            $data['livre_or_image'] = $newName;

            $livre_or_id = $this->model->insert($data);
            $data['livre_or_id'] = $livre_or_id;
        }
        return redirect()->to('/?download_card=' . $livre_or_id);
    }

    // Display images
    public function image($filename)
    {
        $path = WRITEPATH . 'uploads/livre_or_images/' . $filename;
        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $this->response->download($path, null)->setFileName($filename)->setContentType(mime_content_type($path));
    }

    // Truncate the text
    public function truncateText($text, $maxLength) {
        return strlen($text) > $maxLength ? substr($text, 0, $maxLength - 3) . '...' : $text;
    }

    // Generate card
    public function generateCard($id)
    {
        $testimony = $this->model->find($id);

        if (!$testimony) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Témoignage introuvable.");
        }

        $customCardsDir = WRITEPATH . 'uploads/custom_cards/';
        if (!is_dir($customCardsDir)) {
            mkdir($customCardsDir, 0777, true);
        }

        $filename = 'card_' . $testimony['livre_or_id'] . '.png';
        $cardPath = $customCardsDir . $filename;

        // If the card already exists, serve it directly
        if (file_exists($cardPath)) {
            return $this->response->download($cardPath, null)->setFileName($filename)->setContentType('image/png');
        }

        $arialPath = FCPATH . 'assets/fonts/arial.ttf';
        $passionatePath = FCPATH . 'assets/fonts/fellingpassionatefont.ttf';
        $hasImage = !empty($testimony['livre_or_image']);
        $templatePath = $hasImage
            ? FCPATH . 'assets/templates/tpl_with_image.png'
            : FCPATH . 'assets/templates/tpl_without_image.png';

        $image = imagecreatefrompng($templatePath);
        $black = imagecolorallocate($image, 0, 0, 0);

        //Blue color four the city
        $city_colour = imagecolorallocate($image, 40, 74, 143);

        // Get the datas
        $name = $this->truncateText($testimony['livre_or_name'], 30);
        $clubName = $this->truncateText($testimony['livre_or_club_name'], 45);
        $message = $testimony['livre_or_message'];
        $city = $testimony['livre_or_city'];

        // Define the position in function of the template
        if ($hasImage) {
            // Positions if the template has an image
            $nameX = 390; $nameY = 150; $nameSize = 20;
            $clubX = 390; $clubY = 205; $clubSize = 20;
            $msgX = 400; $msgY = 300; $msgSize = 12;
            $cityX = 690; $cityY = 599; $citySize = 18;
            $msgLineHeight = 25;
        } else {
            // Positions if the template has no image
            $nameX = 130; $nameY = 310; $nameSize = 35;
            $clubX = 130; $clubY = 390; $clubSize = 35;
            $msgX = 150; $msgY = 600; $msgSize = 30;
            $cityX = 1250; $cityY = 1100; $citySize = 35;
            $msgLineHeight = 40;
        }

        // Write the text on the image
        imagettftext($image, $nameSize, 0, $nameX, $nameY, $black, $arialPath, $name);
        imagettftext($image, $clubSize, 0, $clubX, $clubY, $black, $arialPath, $clubName);
        imagettftext($image, $citySize, 8, $cityX, $cityY, $city_colour, $passionatePath, $city);

        // Message with many lines
        if ($hasImage){
            $wrapped = wordwrap($message, 48, "\n", true);
        }else{
            $wrapped = wordwrap($message, 65, "\n", true);
        }

        $lines = explode("\n", $wrapped);
        $y = $msgY;
        foreach ($lines as $line) {
            imagettftext($image, $msgSize, 0, $msgX, $y, $black, $arialPath, $line);
            $y += $msgLineHeight;
        }

        // Add image if there is one
        if ($hasImage) {
            $userImagePath = WRITEPATH . 'uploads/livre_or_images/' . $testimony['livre_or_image'];
            if (file_exists($userImagePath)) {
                $userImage = imagecreatefromstring(file_get_contents($userImagePath));
                $origWidth = imagesx($userImage);
                $origHeight = imagesy($userImage);

               // $targetHeight = 290;
               // $targetWidth = intval(($targetHeight / $origHeight) * $origWidth); // conserve le ratio

                // Dimensions du PNG cible
                $pngWidth = imagesx($image);
                $pngHeight = imagesy($image);
                
                // Dimensions originales de l'image utilisateur
                $origWidth = imagesx($userImage);
                $origHeight = imagesy($userImage);
                
                // Taille maximale autorisée dans le PNG (par exemple un cadre de 200x290)
                $maxWidth = 200;
                $maxHeight = 290;
                
                // Calcul du ratio
                $ratio = $origWidth / $origHeight;
                
                // Calcul de la taille redimensionnée
                if ($maxWidth / $maxHeight > $ratio) {
                    $targetHeight = $maxHeight;
                    $targetWidth = intval($maxHeight * $ratio);
                } else {
                    $targetWidth = $maxWidth;
                    $targetHeight = intval($maxWidth / $ratio);
                }

                // $resizedImage = imagescale($userImage, $targetWidth, $targetHeight);

                // // Position pour centrer l’image sur le PNG
                // $dstX = intval(($targetWidth - $targetWidth) / 2);
                // $dstY = intval(($targetHeight - $targetHeight) / 2);
                // imagecopy($image, $resizedImage, $dstX, $dstY, 70, 130, $targetWidth, $targetHeight);

                $resizedImage = imagescale($userImage, $targetWidth, $targetHeight);
 
                // Position pour centrer l’image sur le PNG
                // $dstX = intval(($pngWidth - $targetWidth) / 2);
                // $dstY = intval(($pngHeight - $targetHeight) / 2);

                // Fusion des images
                imagecopy($image, $resizedImage, 70,130, 0, 0, $targetWidth, $targetHeight);

                // imagecopy($image, $resizedImage, 70, 130, 0, 0, $targetWidth, $targetHeight);
                imagedestroy($userImage);
                imagedestroy($resizedImage);
            }
        }

        // Download the card generated
        $isDownload = $this->request->getGet('download');
        if ($isDownload) {
            header('Content-Disposition: attachment; filename="carte_'.$testimony['livre_or_name'].'.png"');
        }

        // Display the generated card
        header('Content-Type: image/png');
        imagepng($image, $cardPath);
        imagedestroy($image);
        if ($this->request->getGet('download')) {
            return $this->response
                ->setHeader('Content-Type', 'image/png')
                ->setHeader('Content-Disposition', 'attachment; filename="carte_'.$testimony['livre_or_name'].'.png"')
                ->setBody(file_get_contents($cardPath));
        }

        // Sinon, affichage direct dans <img>
            return $this->response
                ->setHeader('Content-Type', 'image/png')
                ->setBody(file_get_contents($cardPath));
    }
}
