<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Models\User;
use App\Notifications\NewUserChangePasswordNotification;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService.
 */
class UserService extends BaseService
{
    /**
     * UserService constructor.
     *
     * @param  User  $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param  array  $data
     * @param  int  $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(array $data, $paginate = 15)
    {
        $query = $this->model->query();

        if (! empty($data) && ! empty($data['search'])) {
            $query->where('name', 'LIKE', '%'.$data['search'].'%')
            ->orWhere('email', 'LIKE', '%'.$data['search'].'%')
            ->orWhere('tel', 'LIKE', '%'.$data['search'].'%');
        }

        return $query->paginate($paginate)->withQueryString();
    }

    /**
     * @param    $data
     * @return User
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store($data): User
    {
        DB::beginTransaction();

        try {
            $user = $this->createUser([
                'name' => $data->name,
                'tel' => $data->tel,
                'email' => $data->email,
                'password' => bcrypt($data->password),
                'active' => $data->active,
            ]);

            $user->syncPermissions($data->permissions);

            \Password::sendResetLink(['email' => $data->email], function ($user, $token) {
                $user->notify(new NewUserChangePasswordNotification($token));
            });
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem creating this user. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param    $data
     * @param  User  $user
     * @return User
     *
     * @throws \Throwable
     */
    public function update($data, User $user): User
    {
        DB::beginTransaction();

        try {
            $userData = [
                'name' => $data->name,
                'tel' => $data->tel,
                'email' => $data->email,
                'active' => $data->active,
            ];

            if (! empty($data->password)) {
                $userData = array_merge($userData, [
                    'password' => bcrypt($data->password),
                ]);
            }

            $user->update($userData);

            $user->syncPermissions($data->permissions ?? []);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException('There was a problem updating this user. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param  array  $data
     * @return User
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function updateAccount(array $data = []): User
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $user->update([
                'tel' => $data['tel'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem updating this account. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param    $data
     * @return User
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function uploadImage($data): User
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $path = $user->id.'/profile';

            $picture = $data->file('picture')->storePubliclyAs($path, 'profile_image.'.$data->file('picture')->extension());

            $user->update([
                'picture_url' => $picture,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem uploading this image. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param  array  $data
     * @return User
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function changeFontSize(array $data = []): User
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $user->update([
                'font_size' => $data['font_size'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem changing the font size. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param  array  $data
     * @return User
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function changePassword(array $data = []): User
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $user->update([
                'password' => bcrypt($data['password']),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem changing this password. Please try again.');
        }

        DB::commit();

        return $user;
    }

    /**
     * @param  array  $data
     * @return void
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function sendPasswordReset(array $data = [])
    {
        DB::beginTransaction();

        try {
            \Password::sendResetLink(['email' => $data['email']]);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem sending a password reset link. Please try again.');
        }

        DB::commit();
    }

    /**
     * @param  array  $data
     * @return void
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function resetPassword(array $data = [])
    {
        DB::beginTransaction();

        try {
            $status = \Password::reset([
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ], function ($user, $password) {
                $user->forceFill([
                    'password' => \Hash::make($password),
                ])->setRememberToken(\Str::random(60));

                $user->save();
            });
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem sending a password reset link. Please try again.');
        }

        if ($status == \Password::INVALID_TOKEN) {
            throw new GeneralException('Token mismatch.', 422);
        }

        if ($status == \Password::INVALID_USER) {
            throw new GeneralException('User not found.', 422);
        }

        DB::commit();
    }

    /**
     * @param  array  $data
     * @return User
     */
    protected function createUser(array $data = []): User
    {
        return $this->model::create([
            'name' => $data['name'] ?? null,
            'picture_url' => $data['picture_url'] ?? null,
            'tel' => $data['tel'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'email_verified_at' => now(),
            'active' => $data['active'] ?? null,
        ]);
    }
}
