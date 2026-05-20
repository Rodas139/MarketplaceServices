<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentService
{
    /**
     * Get all comments
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllComments()
    {
        return Comment::query()->orderBy('id')->get();
    }

    /**
     * Get comment by ID
     * 
     * @param int $commentId
     * @return Comment
     */
    public function getCommentById($commentId)
    {
        return Comment::findOrFail($commentId);
    }

    /**
     * Create a new comment
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return Comment
     */
    public function createComment(Request $request)
    {
        $comment = new Comment();
        return $this->saveCommentData($comment, $request);
    }

    /**
     * Update existing comment
     * 
     * @param int $commentId
     * @param  \Illuminate\Http\Request  $request
     * @return Comment
     */
    public function updateComment($commentId, Request $request)
    {
        $comment = $this->getCommentById($commentId);
        return $this->saveCommentData($comment, $request);
    }

    /**
     * Save comment data with image handling and validation
     * @param Comment $comment
     * @param Request $request
     * @return Comment
     */
    private function saveCommentData(Comment $comment, Request $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uploadDir = public_path('uploads/comments');
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadDir, $imageName);
            $input['image'] = 'uploads/comments/' . $imageName;
        }

        if ($comment->validate($input)) {
            $comment->fill($input);
            $comment->save();
            return $comment;
        }

        throw new \Exception($comment->errors()->first());
    }

    /**
     * Delete a single comment
     * 
     * @param int $commentId
     * @return bool
     */
    public function deleteComment($commentId)
    {
        $comment = $this->getCommentById($commentId);

        if (!$comment->delete()) {
            throw new \Exception('Ocorreu um erro ao tentar remover o comentário.');
        }

        return true;
    }

    /**
     * Delete multiple comments
     * 
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleComments(array $ids)
    {
        $result = Comment::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new \Exception('Não foi possível remover os registos selecionados');
        }

        return true;
    }

    /**
     * Get datatable data for comments
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Comment::query();
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
            'action' => 'Novo Comentário',
            'comment' => new Comment(),
            'customers' => Customer::pluck('name', 'id')->toArray(),
            'orders' => Order::pluck('id', 'id')->toArray(),
            'formOptions' => [
                'route' => ['admin.comments.store'],
                'files' => true,
                'enctype' => 'multipart/form-data'
            ]
        ];
    }

    /**
     * Get data for edit form
     * 
     * @param int $commentId
      * @return array
     */
    public function getEditData($commentId)
    {
        $comment = $this->getCommentById($commentId);

        return [
            'action' => 'Editar Comentário',
            'comment' => $comment,
            'customers' => Customer::pluck('name', 'id')->toArray(),
            'orders' => Order::pluck('id', 'id')->toArray(),
            'formOptions' => [
                'route' => ['admin.comments.update', $comment->id],
                'method' => 'PUT',
                'files' => true,
                'enctype' => 'multipart/form-data'
            ]
        ];
    }
}
