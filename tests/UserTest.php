<?php

use PHPUnit\Framework\TestCase;
use App\User\InMemoryUserRepository;
use App\User\UserService;

use PHPUnit\Framework\Attributes\DataProvider;
class UserTest extends TestCase{
    public static function emailsproviders(){
        return [
            "failed#1" => ["yo.reww@gmail.com" , true],
      
             "failed#2" => ["yo.r2eww@gmail.com" , true]
        ];
    }
        #[DataProvider('emailsproviders')]
    public function testsaveuser($email,$check){
        //arrange
        $id= 6;
        $email = $email;
        $user = ["email"=>$email,"id"=>$id];
          $stubRepo = $this->createStub(InMemoryUserRepository::class);
    // برمجنا البغبغان: لما حد ينادي عليك دايماً رجع true
    $stubRepo->method('emailExists')
             ->willReturn($check);

        //act
            $this->expectException(InvalidArgumentException::class);
        $userservice = new UserService($stubRepo);
        $userservice->addUser($user);


        //assert


    }
}