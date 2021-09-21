<?php

declare( strict_types=1 );

namespace lib\controllers;

class CategoryApiController extends CategoryController
{
    /**
     * @return array
     */
    public function getList(): array
    {
        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();
        $categories      = $categoriesModel->getList();

        // відповідь
        $response        = [];

        $response['status'] = 200;

        foreach( $categories as $category )
        {
            $response['data'][] =
            [
                'id'    => $category->getId(),
                'title' => $category->getTitle(),
                'alias' => $category->getAlias(),
            ];
        }

        return $response;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getSingle( int $id ): array
    {
        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();
        $category        = $categoriesModel->getSingle( $id );

        // відповідь
        $response        = [];

        if( !empty( $category ) )
        {
            $response['status'] = 200;

            $response['data']   =
            [
                'id'    => $category->getId(),
                'title' => $category->getTitle(),
                'alias' => $category->getAlias(),
            ];
        }
        else
        {
            $response['status'] = 404;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function create(): array
    {
        $response = [];

        $title  = $_POST['title'] ?? '';
        $alias  = $_POST['alias'] ?? '';

        if( !$this->_validateTitle( $title ) )
        {
            $response['status'] = 411;
            $response['error']  = $this->_error_message;
            return $response;
        }

        if( !$this->_validateAlias( $alias ) )
        {
            $response['status'] = 412;
            $response['error']  = $this->_error_message;
            return $response;
        }

        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();

        // перевіряємо на існування категорії з таким же самим alias (оскільки alias це UNIQUE)
        $result = $categoriesModel->existsByAlias( $alias );

        if( $result )
        {
            $response['status'] = 450;
            $response['error']  = 'Категорія з таким alias вже існує';
            return $response;
        }

        // додаємо нову категорію
        $result = $categoriesModel->add( $title, $alias );

        if( empty( $result ) )
        {
            $response['status'] = 460;
            $response['error']  = 'Сталася помилка при додаванні категорії';
            return $response;
        }

        $response['status'] = 200;

        // отримуємо категорію з БД за ID (додане значення)
        $category        = $categoriesModel->getSingle( $result->getId() );

        $response['data']   =
        [
            'id'    => $category->getId(),
            'title' => $category->getTitle(),
            'alias' => $category->getAlias(),
        ];

        return $response;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function update( int $id ): array
    {
        $response = [];

        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();

        // отримуємо категорію з БД за ID
        $category = $categoriesModel->getSingle( $id );

        if( empty( $category ) )
        {
            $response['status'] = 404;
            $response['error']  = 'Категорії з таким ID не існує';
            return $response;
        }

        // У PHP при використанні методу PUT немає окремої суперглобальної змінної для цього.
        // Тому тут ми її емулюємо таким чином:
        parse_str( file_get_contents( "php://input" ), $_PUT );

        $title  = $_PUT['title'] ?? '';
        $alias  = $_PUT['alias'] ?? '';

        if( !$this->_validateTitle( $title ) )
        {
            $response['status'] = 411;
            $response['error']  = $this->_error_message;
            return $response;
        }

        if( !$this->_validateAlias( $alias ) )
        {
            $response['status'] = 412;
            $response['error']  = $this->_error_message;
            return $response;
        }

        // перевіряємо на існування категорії з таким же самим alias (оскільки alias це UNIQUE), але іншим ID
        $result = $categoriesModel->existsByAliasExceptID( $alias, $id );

        if( $result )
        {
            $response['status'] = 450;
            $response['error']  = 'Категорія з таким alias вже існує';
            return $response;
        }

        // редагуємо категорію
        $result = $categoriesModel->edit( $id, $title, $alias );

        if( empty( $result ) )
        {
            $response['status'] = 460;
            $response['error']  = 'Сталася помилка при редагуванні категорії';
            return $response;
        }

        $response['status'] = 200;

        // отримуємо категорію з БД за ID (оновлене значення)
        $category        = $categoriesModel->getSingle( $id );

        $response['data']   =
        [
            'id'    => $category->getId(),
            'title' => $category->getTitle(),
            'alias' => $category->getAlias(),
        ];

        return $response;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function delete( int $id ): array
    {
        $response = [];

        // створюємо екземпляр моделі
        $categoriesModel = new \lib\models\CategoriesModel();

        // отримуємо категорію з БД за ID
        $category = $categoriesModel->getSingle( $id );

        if( empty( $category ) )
        {
            $response['status'] = 404;
            $response['error']  = 'Категорії з таким ID не існує';
            return $response;
        }

        // видаляємо категорію з БД за ID
        $categoriesModel->delete( $id );

        $response['status'] = 200;

        return $response;
    }
}