<?php
declare(strict_types=1);
abstract class User
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    public $username;
    public $status;
function __construct($username, $status = self::STATUS_ACTIVE)
{
    $this->username = $username;
    $this->status = $status;
}
public function printStatus()
{
    echo $this->status;
}
}
class Admin extends User
{
// ...
public function printCustomStatus()
{
echo "L'administrateur {$this->username} a pour statut : ";
$this->printStatus(); // appelle printStatus du parent depuis l'enfant
}
}
$admin = new Admin('Lily');
$user = new USer('Lily');
// Affiche "L'administrateur Lily a pour statut : active"
$admin->printCustomStatus();
// printStatus n'existe pas dans la classe Admin,
// donc printStatus de la classe User sera appelée grâce à l'héritage
$admin->printStatus();