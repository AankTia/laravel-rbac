# TODO

## Model

-   [ ] Create base model

## Controller

-   [x] Refactor auth()->user()->hasPermission, use helper

## Helper

-   [ ] check permission for each buttons

## Role

-   [x] route, use slug instead of id
-   [x] Update slug when role name updated
-   [x] add breadcrumb
-   [x] create helper for auth()->user()->hasPermission in blade
-   [x] Update permission activity log
-   [x] Activity log detail modal
-   [x] paginate activity logs
-   [x] Detail actiity page
-   [x] Delete user from role

## Activity Log

-   [x] Activate
-   [x] Deactivate
-   [ ] enhance data structure, different info for user timeline and module timeline

## Enhance Log activity

### user

-   [x] user - Auth\LoginController@login
-   [x] user - logout › Auth\LoginController@logout
-   [ ] user - Auth\ConfirmPasswordController@confirm
-   [ ] user - password.email › Auth\ForgotPasswordController@sendResetLinkEmail
-   [ ] user - password.update › Auth\ResetPasswordController@reset
-   [ ] user - Auth\RegisterController@register
-   [x] user - users.store › UserController@store
-   [x] user - users.update › UserController@update
-   [ ] user - users.destroy › UserController@destroy
-   [ ] user - users.activate › UserController@activate
-   [ ] user - users.deactivate › UserController@deactivate

### role

-   [ ] role - roles.store › RoleController@store
-   [ ] role - roles.update › RoleController@update
-   [ ] role - roles.destroy › RoleController@destroy
-   [ ] role - roles.update-permissions › RoleController@updatePermissions
-   [ ] role - roles.delete-user › RoleController@deleteUser

## User

-   [ ] Enhance button to use button helper
-   [ ] dynamic user profile

## User profile

-   [ ] show active sessions

## Seeder

-   [ ] Insert logs activity

## dashboard layout

-   [ ] split contents

## Sidebar

-   [ ] make it fix position

## Top bar

-   [ ] make it fix position
