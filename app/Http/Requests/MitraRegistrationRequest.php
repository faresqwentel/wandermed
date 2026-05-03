<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitraRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nama_penanggung_jawab' => 'required|string|max:100',
            'email'                 => 'required|email|unique:mitras,email',
            'password'              => 'required|min:8|confirmed',
            'no_telp'               => 'required|string|max:20',
            'jenis_mitra'           => 'required|in:faskes,pariwisata',
            
            // Faskes attributes
            'nama_faskes'           => 'nullable|string|max:150',
            'jenis_faskes'          => 'nullable|string',
            'layanan_ugd'           => 'nullable|string',
            'dukungan_bpjs'         => 'nullable',
            
            // Pariwisata attributes
            'nama_pariwisata'       => 'nullable|string|max:150',
            'jenis_wisata'          => 'nullable|string',
            'deskripsi'             => 'nullable|string',
            
            // Shared attributes
            'alamat'                => 'required|string',
            'latitude'              => 'required|numeric',
            'longitude'             => 'required|numeric',
            'dokumen_izin'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
