<!-- Попов Даниил 112 МКО -->

<?php

// основной класс для работы с графами (неориентироанными)
class undirectedGraph
{
    protected $vertexes;          // число вершин
    protected $matrix_adjacency;  // матрица смежности

    public function __construct($vertexes) { // конструктор
        $this->vertexes = $vertexes;

        $temp_matr[$vertexes][$vertexes];

        for($i = 0; $i < $vertexes; $i++) {
            for($j = 0; $j < $vertexes; $j++) {
                if($i == $j) {
                    $temp_matr[$i][$j] = 1; // по умолчанию считаем, что из вершины в саму себя попасть можно
                } else {
                    $temp_matr[$i][$j] = 0; // нет ребра
                }
            }
        }

        $this->matrix_adjacency = $temp_matr;
    }

    public function get_matrix() {      // получить матрицу смежности
        return $this->matrix_adjacency;
    }

    public function print_matrix() {    // напечатать матрицу смежности
        echo '<table class="matrix"><tbody>';
        foreach($this->matrix_adjacency as $r){
            echo '<tr>';
            foreach($r as $el){
                // echo $el . '  ';
                echo '<td> ' . $el . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    public function get_vertexes() {    // получить кол-во вершин
        return $this->vertexes;
    }

    // добавить ребро
    public function add_edge($v1, $v2, $weight=1) {
        if ($v1 < $this->vertexes and $v2 < $this->vertexes and $v1 >= 0 and $v2 >= 0) {
            $this->matrix_adjacency[$v1][$v2] = $weight;
            $this->matrix_adjacency[$v2][$v1] = $weight;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

    // удалить ребро
    public function del_edge($v1, $v2) {
        if ($v1 < $this->vertexes and $v2 < $this->vertexes and $v1 >= 0 and $v2 >= 0) {
            $this->matrix_adjacency[$v1][$v2] = 0;
            $this->matrix_adjacency[$v2][$v1] = 0;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

    // добавление вершины
    public function add_vertexes() { // добавим новую изолированную вершину
        $new_matrix = [];
        foreach($this->matrix_adjacency as $row) { // новый столбец
            array_push($row, 0);
            array_push($new_matrix, $row);
        }
        $new_row = [];
        for($i = 0; $i < $this->vertexes; $i++) {
            array_push($new_row, 0);
        }
        array_push($new_row, 1);
        array_push($new_matrix, $new_row);

        $this->matrix_adjacency = $new_matrix;
        $this->vertexes = $this->vertexes + 1;
    }

    // удаление вершины
    public function del_vertexes($v) {
        $new_matrix = [];
        if ($v < $this->vertexes and $v >= 0) {
            foreach($this->matrix_adjacency as $row) {
                array_splice($row, $v, 1);
                array_push($new_matrix, $row);
            }
            array_splice($new_matrix, $v, 1);

            $this->matrix_adjacency = $new_matrix;
            $this->vertexes = $this->vertexes - 1;
        }
    }

    // TODO  проверка на двудольность
    public function is_bipartite() {
        echo '<p>Наверное, двудольный, или нет</p>';
    }

    // проверка на полноту
    public function is_full() {
        foreach($this->matrix_adjacency as $r){
            foreach($r as $el){
                if($el == 0) {
                    return 0;
                }
            }
        }
        return 1;
    }

    // расчет валентности вершины
    public function get_degree($v) {
        if ($v < $this->vertexes and $v >= 0) {
            $sum = 0;
            for($i = 0; $i < $this->vertexes; $i++) {
                if($i != $v && $this->matrix_adjacency[$i][$v] > 0)
                    $sum++;
            }
            return $sum;
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на висячесть
    public function is_hanging($v) {
        if ($v < $this->vertexes and $v >= 0) {
            return $this->get_degree($v) == 1 ? 1 : 0; // 1 - висячая, 0 - нет
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на изолированность
    public function is_isolated($v) {
        if ($v < $this->vertexes and $v >= 0) {
            return $this->get_degree($v) ? 0 : 1; // 1 - изолированная, 0 - нет
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на r-однородность
    public function is_regular() {
        $r = $this->get_degree(0);
        for($i = 1; $i < $this->vertexes; $i++)
            if($r != $this->get_degree($i))
                return -1;
        return $r;
    }

    // проверка вершин на смежность
    public function vertexes_adjacent($v1, $v2) {
        if ($v1 < $this->vertexes and $v1 >= 0 and $v2 < $this->vertexes and $v2 >= 0) {
            if($this->matrix_adjacency[$v1][$v2] >= 1)
                return 1;
            return 0;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

    // проверка ребер на смежность
    public function edges_adjacent($v11, $v12, $v21, $v22) {
        $matr = $this->matrix_adjacency;
        if ($v11 < $this->vertexes and $v11 >= 0 and $v12 < $this->vertexes and $v12 >= 0 and $v21 < $this->vertexes and $v21 >= 0 and $v22 < $this->vertexes and $v22 >= 0) {
            if(($v11 == $v21 || $v11 == $v22 || $v12 == $v21 || $v12 == $v22) && ($matr[$v11][$v12] >= 1 && $matr[$v21][$v22] >= 1))
                return 1;
            return 0;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

}

// класс для ориентированных ребер
class orientedGraph extends undirectedGraph
{
    // добавить ребро
    public function add_edge($v1, $v2, $weight=1) {
        if ($v1 < $this->vertexes and $v2 < $this->vertexes and $v1 >= 0 and $v2 >= 0) {
            $this->matrix_adjacency[$v1][$v2] = $weight;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

    // удалить ребро
    public function del_edge($v1, $v2) {
        if ($v1 < $this->vertexes and $v2 < $this->vertexes and $v1 >= 0 and $v2 >= 0) {
            $this->matrix_adjacency[$v1][$v2] = 0;
        } else
            echo 'Одна из вершин не принадлежит графу!</br>';
    }

    // расчет валентности вершины, взвращает 2 значения - степень входа и выхода
    public function get_degree($v) {
        if ($v < $this->vertexes and $v >= 0) {
            $sum_out = 0;
            $sum_in = 0;
            for($i = 0; $i < $this->vertexes; $i++) {
                if($i != $v && $this->matrix_adjacency[$v][$i] > 0)
                    $sum_out++;
                if($i != $v && $this->matrix_adjacency[$i][$v] > 0)
                    $sum_in++;
            }
            return [$sum_in, $sum_out];
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на висячесть
    public function is_hanging($v) {
        if ($v < $this->vertexes and $v >= 0) {
            return $this->get_degree($v)[0] == 1 && $this->get_degree($v)[1] == 1 ? 1 : 0; // 1 - висячая, 0 - нет
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на изолированность
    public function is_isolated($v) {
        if ($v < $this->vertexes and $v >= 0) {
            return $this->get_degree($v)[0] == 0 && $this->get_degree($v)[1] == 0 ? 1 : 0; // 1 - изолированная, 0 - нет
        } else
            echo 'Вершина не принадлежит графу!</br>';
    }

    // проверка на r-однородность
    public function is_regular() {
        return 0;
    }

}

if(isset($_GET['vert'])){
    // неориент. граф
    echo '<h1>НЕОРИЕНТИРОВАННЫЙ ГРАФ</h1>';
    $objGraph = new undirectedGraph($_GET['vert']);

    // вывод матрицы
    echo '<p>Матрица смежности изначальная</p>';
    $objGraph->print_matrix();
    // добавить ребра
    $objGraph->add_edge(0, 1, 3);
    $objGraph->add_edge(0, 3, 2);
    $objGraph->add_edge(1, 3);
    $objGraph->add_edge(0, 4);
    $objGraph->add_edge(0, 4);
    $objGraph->add_edge(2, 4, 10);
    echo '<p>Матрица смежности после добавления ребер</p>';
    $objGraph->print_matrix();
    // удалить ребра
    $objGraph->add_edge(2, 4);
    echo '<p>Матрица смежности после удаления ребер</p>';
    $objGraph->print_matrix();
    // добавить вершину
    $objGraph->add_vertexes();
    $objGraph->add_edge(5, 4);
    $objGraph->add_edge(5, 3, 10);
    echo '<p>Матрица смежности после добавления вершины</p>';
    $objGraph->print_matrix();
    // удалить вершину
    $objGraph->del_vertexes(4);
    echo '<p>Матрица смежности после удаления вершины</p>';
    $objGraph->print_matrix();
    // проверка на двудольность
    $objGraph->is_bipartite();
    // проверка на полноту
    if($objGraph->is_full())
        echo '<p>Граф полный</p>';
    else
        echo '<p>Граф неполный</p>';
    // расчет валентности вершин
    echo '<p>Валентность вершин: v0 - '. $objGraph->get_degree(0) . ', v1 - '. $objGraph->get_degree(1) . ', v2 - '. $objGraph->get_degree(2) . ', v3 - '. $objGraph->get_degree(3) . ', v4 - '. $objGraph->get_degree(4) .'</p>';
    // проверка на висячесть вершин
    echo '<p>Висячесть вершин: v0 - '. ($objGraph->is_hanging(0) ? 'да' : 'нет') . ', v4 - '. ($objGraph->is_hanging(4) ? 'да' : 'нет') . '</p>';
    // Проерка на изолированность вершин
    echo '<p>Изолированность вершин: v2 - '. ($objGraph->is_isolated(2) ? 'да' : 'нет') . ', v4 - '. ($objGraph->is_isolated(4) ? 'да' : 'нет') . '</p>';
    // Проерка на r-однородность
    echo '<p>Граф r-однородный: '. ($objGraph->is_regular() == -1 ? 'нет' : 'да, r =' . $objGraph->is_regular()) . '</p>';
    // проверка вершин на смежность
    echo '<p>Вершины v0 и v1 '. ($objGraph->vertexes_adjacent(0, 1) ? '' : 'не' ) . 'смежны</p>';
    echo '<p>Вершины v0 и v4 '. ($objGraph->vertexes_adjacent(0, 4) ? '' : 'не' ) . 'смежны</p>';
    // проверка ребер на смежность
    echo '<p>Ребра e01 и e13 '. ($objGraph->edges_adjacent(0, 1, 1, 3) ? '' : 'не' ) . 'смежны</p>';
    echo '<p>Ребра e01 и e35 '. ($objGraph->edges_adjacent(0, 1, 3, 5) ? '' : 'не' ) . 'смежны</p>';
    echo '<p>Ребра e01 и e34 '. ($objGraph->edges_adjacent(0, 1, 3, 4) ? '' : 'не' ) . 'смежны</p>';

}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реализация графов с помощью ООП</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="text-align:center;">

<form class="form" method="get" action="index.php">
    <input class="text_create" type="text" size="5" name="vert" /> <span> - количество вершин </span>
    <input class="btn_create" type="submit" value="Создать граф" />
</form>

</body>
</html>
