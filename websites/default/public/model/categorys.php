<?php
class CategoryModel extends database
{

    function __construct()
    {
        $this->connect('categorys');
    }

    function getCategoryOfProduct($id)
    {
        if (is_null($this->pdo)) return NULL;

        $stmt = $this
            ->pdo
            ->prepare("SELECT c.id, c.name FROM categorys c
                                             INNER JOIN categorys_link_products cp ON cp.category_id = c.id
                                             INNER JOIN products p ON p.id = cp.product_id
                                             WHERE p.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
?>
