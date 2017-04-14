<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Form\CreateUserForm;

class UsersController extends AppController {
  public function index() {
    $users = $this->paginate($this->Users);

    $this->set(compact('users'));
    $this->set('_serialize', ['users']);
  }

  public function view($id = null) {
    $user = $this->Users->get($id, ['contain' => []]);

    $this->set('user', $user);
    $this->set('_serialize', ['user']);
  }

  public function add() {
    $form = new CreateUserForm();

    if ($this->request->is('post')) {
      $data = $this->request->getData();

      if ($form->validate($data)) {
        if ($form->execute($data)) {
          $this->Flash->success(__('The user has been saved.'));

          return $this->redirect(['action' => 'index']);
        } else {
          $this
            ->Flash
              ->error(__('The user could not be saved. Please, try again.'));
        }
      }
    }

    $this->set('form', $form);
  }

  public function edit($id = null) {
    $user = $this->Users->get($id, ['contain' => []]);

    if ($this->request->is(['patch', 'post', 'put'])) {
        $user = $this->Users->patchEntity($user, $this->request->getData());
        $user['password'] = Security::hash($user['password'], 'md5', true);

        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this
          ->Flash
            ->error(__('The user could not be saved. Please, try again.'));
    }

    $this->set(compact('user'));
    $this->set('_serialize', ['user']);
  }
}
