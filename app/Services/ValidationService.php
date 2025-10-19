<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    public function validateCctv(array $data, ?int $id = null): array
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'firmware_version' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ip_rtsp' => 'required|string|max:255',
            'stream_username' => 'nullable|string|max:255',
            'stream_password' => 'nullable|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'resolution' => 'nullable|string|max:50',
            'fps' => 'required|integer|min:1|max:120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        // For updates, make some fields optional
        if ($id) {
            $rules['building_id'] = 'sometimes|exists:buildings,id';
            $rules['name'] = 'sometimes|string|max:255';
            $rules['ip_rtsp'] = 'sometimes|string|max:255';
            $rules['port'] = 'sometimes|integer|min:1|max:65535';
            $rules['fps'] = 'sometimes|integer|min:1|max:120';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateBuilding(array $data, ?int $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];

        // For updates, make name sometimes required
        if ($id) {
            $rules['name'] = 'sometimes|string|max:255';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateRoom(array $data, ?int $id = null): array
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'floor' => 'nullable|integer',
            'capacity' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        // For updates, make some fields optional
        if ($id) {
            $rules['building_id'] = 'sometimes|exists:buildings,id';
            $rules['name'] = 'sometimes|string|max:255';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateMaintenance(array $data, ?int $id = null): array
    {
        $rules = [
            'cctv_id' => 'required|exists:cctvs,id',
            'technician_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'type' => 'required|in:preventive,corrective,emergency',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ];

        // For updates, make some fields optional
        if ($id) {
            $rules['cctv_id'] = 'sometimes|exists:cctvs,id';
            $rules['type'] = 'sometimes|in:preventive,corrective,emergency';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateAlert(array $data, ?int $id = null): array
    {
        $rules = [
            'alertable_type' => 'required|string',
            'alertable_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'severity' => 'required|in:critical,high,medium,low',
            'category' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
        ];

        // For updates, make some fields optional
        if ($id) {
            $rules['title'] = 'sometimes|string|max:255';
            $rules['message'] = 'sometimes|string';
            $rules['severity'] = 'sometimes|in:critical,high,medium,low';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateUser(array $data, ?int $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
        ];

        // For updates, adjust rules
        if ($id) {
            $rules['email'] = 'sometimes|email|max:255|unique:users,email,'.$id;
            $rules['password'] = 'sometimes|string|min:8|confirmed';
            $rules['name'] = 'sometimes|string|max:255';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateContact(array $data, ?int $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
        ];

        // For updates, make name sometimes required
        if ($id) {
            $rules['name'] = 'sometimes|string|max:255';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
