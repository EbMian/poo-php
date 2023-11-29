<?php
declare(strict_types=1);
//echo "oui";

class User
{

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    public $username;
    public $status;
    public function __construct($username, $status = self::STATUS_ACTIVE)
    {
    }
}
class Admin extends User
{
    // ...
    public function printStatus()
    {
        // vous pouvez accéder au statut comme si la propriété appartenait à Admin
        echo $this->status;
    }
}
$admin = new Admin('Lily');
$admin->printStatus();
