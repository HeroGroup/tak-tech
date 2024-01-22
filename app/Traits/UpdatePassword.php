<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait UpdatePassword {
  public function updateUserPassword($request, $user): array
  {
    try {
      if (Hash::check($request->current_password, $user->password)) {
        if ($request->password == $request->password_confirmation) {
            $user->password = Hash::make($request->password);
            $user->save();
  
            return [
              'message' => 'رمز عبور با موفقیت به روزرسانی شد.',
              'type' => 'success'
            ];
        } else {
          return [
            'message' => 'رمز عبور جدید و تکرار آن مطابقت ندارند.',
            'type' => 'error'
          ];
        }
      } else {
        return [
          'message' => 'رمز عبور فعلی نادرست است.',
          'type' => 'error'
        ];
      }
    } catch (\Exception $exception) {
      return [
        'message' => $exception->getMessage(),
        'type' => 'error'
      ];
    }
  }
}