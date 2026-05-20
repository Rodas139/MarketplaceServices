<?php

namespace App\Services;

use App\Models\Comission;
use App\Models\Payment;
use Illuminate\Http\Request;

class ComissionService
{
    /**
     * Get all comissions
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllComissions()
    {
        return Comission::query()->orderBy('id')->get();
    }

    /**
     * Get comission by ID
     * 
     * @param int $comissionId
     * @return Comission
     */
    public function getComissionById($comissionId)
    {
        return Comission::findOrFail($comissionId);
    }

    /**
     * Create a new comission
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return Comission
     */
    public function createComission(Request $request)
    {
        $comission = new Comission();
        return $this->saveComissionData($comission, $request);
    }

    /**
     * Update existing comission
     * 
     * @param int $comissionId
     * @param  \Illuminate\Http\Request  $request
     * @return Comission
     */
    public function updateComission($comissionId, Request $request)
    {
        $comission = $this->getComissionById($comissionId);
        return $this->saveComissionData($comission, $request);
    }

    /**
     * Save comission data with validation
     * 
     * @param Comission $comission
     * @param  \Illuminate\Http\Request  $request
     * @return Comission
     */
    private function saveComissionData(Comission $comission, Request $request)
    {
        $input = $request->all();

        if ($comission->validate($input)) {
            $comission->fill($input);
            $comission->save();
            return $comission;
        }

        throw new \Exception($comission->errors()->first());
    }

    /**
     * Delete a single comission
     * 
     * @param int $comissionId
     * @return bool
     */
    public function deleteComission($comissionId)
    {
        $comission = $this->getComissionById($comissionId);

        if (!$comission->delete()) {
            throw new \Exception('Ocorreu um erro ao tentar remover a comissão.');
        }

        return true;
    }

    /**
     * Delete multiple comissions
     * 
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleComissions(array $ids)
    {
        $result = Comission::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new \Exception('Não foi possível remover os registos selecionados');
        }

        return true;
    }

    /**
     * Get datatable data for comissions
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Comission::query();
    }

    /**
     * Get data for index view
     * 
     * @return array
     */
    public function getIndexData()
    {
        return [];
    }

    /**
     * Get data for create form
     * 
     * @return array
     */
    public function getCreateData()
    {
        return [
            'action' => 'Nova Comissão',
            'comission' => new Comission(),
            'payments' => Payment::where('status', 'paid')->pluck('id', 'id')->toArray(),
            'formOptions' => [
                'route' => ['admin.comissions.store']
            ]
        ];
    }

    /**
     * Get data for edit form
     * 
     * @param int $comissionId
     * @return array
     */
    public function getEditData($comissionId)
    {
        $comission = $this->getComissionById($comissionId);

        return [
            'action' => 'Editar Comissão',
            'comission' => $comission,
            'payments' => Payment::where('status', 'paid')->pluck('id', 'id')->toArray(),
            'formOptions' => [
                'route' => ['admin.comissions.update', $comission->id],
                'method' => 'PUT'
            ]
        ];
    }
}
