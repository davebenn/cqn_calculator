<?php
class CQN_Name{

    public $title;
    public $forename;
    public $surname;

    public function __construct( $name ){
    /* construct a name [title, forename, surname from a single string*/

        $nameArray = explode( ' ', $name );

        switch( count( $nameArray )  ){

            case 0:
                return false;
                break;

            case 1:

                $this->title    = '';
                $this->forename = $nameArray[0];
                $this->surname  = '';
                break;

            case 2:

                $this->title    = '';
                $this->forename = $nameArray[0];
                $this->surname  = $nameArray[1];
                break;

            case 3:

                $this->title    = $nameArray[0];
                $this->forename = $nameArray[1];
                $this->surname  = $nameArray[2];
                break;

            default:

                $this->title    = $nameArray[0];
                $this->forename = $nameArray[1];
                $this->surname  = $nameArray[2];
                break;

        }
    }

    public function fullname(){


        return trim( $this->title . ' ' . $this->forename . ' ' .$this->surname    );




    }
}

