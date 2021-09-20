<?php

abstract class Person
{
    protected string $_name;

    public function getName() : string
    {
        return $this->_name;
    }

    public function setName( string $name ): void
    {
        $this->_name = $name;
    }
}

class Student extends Person
{
    protected array $_scores = [];

    public function getScores() : array
    {
        return $this->_scores;
    }

    public function setScores( array $scores ):void
    {
        $this->_scores = $scores;
    }

    public function getAverageScore() : int
    {
        if( empty( $this->_scores ) )
        {
            return 0;
        }

        return round( array_sum( $this->_scores ) / count( $this->_scores ) );
    }
}

class Teacher extends Person
{
    protected int $_salary = 0;

    public function getSalary() : int
    {
        return $this->_salary;
    }

    public function setSalary( int $salary ) : void
    {
        $this->_salary = $salary;
    }
}

$s1 = new Student();
$s1->setName('Vasya');
$s1->setScores([ 3,3,3 ]);
echo $s1->getName() . ': '.$s1->getAverageScore() . '<br>';

$s2 = new Student();
$s2->setName( 'Petya' );
$s2->setScores( [5,4,3] );
echo $s2->getName() . ': '.$s2->getAverageScore() . '<br>';

$t1 = new Teacher();
$t1->setName('Inokentyi Inokentievich');
$t1->setSalary(10000);

foreach( [ $t1, $s1, $s2 ] as $person )
{
    echo $person->getName() . '<br>';
}

