<?php

namespace App\Controllers;

use Framework\Authorization;
use Framework\Database;
use Framework\Session;
use Framework\Validation;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show($params)
    {
        $id = $params['id'] ?? '';

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', [
            'id' => $id
        ])->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    public function store()
    {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = Session::get('user')['id'];

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (!Validation::string($newListingData[$field]) || empty($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {

            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            Session::setFlashMessage('success_message', 'Listing created successfully');

            redirect('/listings');
        }
    }

    public function destroy($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
            return redirect('/listings/' . $listing->id);
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        Session::setFlashMessage('success_message', 'Listing deleted successfully');

        redirect('/listings');
    }

    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', [
            'id' => $id
        ])->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    public function update($params)
    {
        $id = $params['id'] ?? '';

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', [
            'id' => $id
        ])->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (!Validation::string($updateValues[$field]) || empty($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            // Add the id back to the update values and convert to object
            $updateValues['id'] = $id;
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => (object) $updateValues
            ]);
            exit;
        } else {

            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;

            $this->db->query($updateQuery, $updateValues);

            Session::setFlashMessage('success_message', 'Listing updated successfully');

            redirect('/listings/' . $id);
        }
    }
}
