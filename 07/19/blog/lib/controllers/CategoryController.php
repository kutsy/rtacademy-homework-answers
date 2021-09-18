<?php

declare( strict_types=1 );

namespace lib\controllers;

class CategoryController
{
    protected const TITLE_MIN_LENGTH = 2;
    protected const TITLE_MAX_LENGTH = 32;
    protected const TITLE_REGEXP     = '/^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,\':;<>\s]+$/iu';

    protected const ALIAS_MIN_LENGTH = 2;
    protected const ALIAS_MAX_LENGTH = 32;
    protected const ALIAS_REGEXP     = '/^[a-z0-9-]+$/';

    protected string $_error_message   = '';
    protected string $_success_message = '';

    protected function _validateTitle( string $title ) : bool
    {
        if( strlen( $title ) < self::TITLE_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Назва"';
            return false;
        }

        if( strlen( $title ) > self::TITLE_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Назва" - ' . self::TITLE_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::TITLE_REGEXP, $title ) )
        {
            $this->_error_message = 'Поле "Назва" містить некоректні символи';
            return false;
        }

        return true;
    }

    protected function _validateAlias( string $alias ) : bool
    {
        if( strlen( $alias ) < self::ALIAS_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Аліас"';
            return false;
        }

        if( strlen( $alias ) > self::ALIAS_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Аліас" - ' . self::ALIAS_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::ALIAS_REGEXP, $alias ) )
        {
            $this->_error_message = 'Поле "Аліас" містить некоректні символи';
            return false;
        }

        return true;
    }

    public function getErrorMessage() : string
    {
        return $this->_error_message;
    }

    public function getSuccessMessage() : string
    {
        return $this->_success_message;
    }

    public function add(): void
    {
        // у випадку неавторизованого користувача - переходимо на першу сторінку
        if( ! \lib\Session::isAuthorized() )
        {
            header( 'Location: ./index.php' );
            return;
        }

        // не виконуємо весь код нижче, якщо форма не заповнена
        if( empty( $_POST ) )
        {
            return;
        }

        $title  = $_POST['title'] ?? '';
        $alias  = $_POST['alias'] ?? '';

        if( !$this->_validateTitle( $title ) )
        {
            return;
        }

        if( !$this->_validateAlias( $alias ) )
        {
            return;
        }

        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();

        // перевіряємо на існування категорії з таким же самим alias (оскільки alias це UNIQUE)
        $result = $categoriesModel->existsByAlias( $alias );

        if( $result )
        {
            $this->_error_message = 'Категорія з таким alias вже існує';
            return;
        }

        // додаємо нову категорію
        $result = $categoriesModel->add( $title, $alias );

        if( empty( $result ) )
        {
            $this->_error_message = 'Сталася помилка при додаванні категорії';
            return;
        }

        // заповнюємо повідомлення про успішне додавання категоії
        $this->_success_message = 'Категорію "' . htmlspecialchars( $title ) . '" успішно додано';
    }

    public function edit( int $id ): void
    {
        // TODO
    }
}