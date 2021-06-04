<?php
include('conexion.php');

require 'fecha.php';
require 'funcs.php';

if (isset($_POST['buscar'])) {
    if (isNullesp($_POST['especialidad'])) {

        echo '<script>
            alert("Seleccionar una Especialidad");
          </script>';
    } else if (isNullgrad($_POST['grado'])) {

        echo '<script>
            alert("Seleccionar un Grado");
          </script>';
    } else if (isNullsec($_POST['seccion'])) {

        echo '<script>
            alert("Seleccionar una Seccion");
          </script>';
    } else {
        $espe = $_POST['especialidad'];
        $grado = $_POST['grado'];
        $secc = $_POST['seccion'];
        //consulta de alumnos
        $sql = "SELECT  id_alumnos, nombres, grado, seccion from alumnos where especialidad = $espe and grado = $grado and seccion = $secc";
        $result = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($result) > 0) {


?>

            <form action="" method="POST">
                <label>Materia</label>
                <?php
                //consulta de materias
                $materia = "SELECT * from materias where especialidad = $espe and grado = $grado";
                $mate = mysqli_query($mysqli, $materia);

                if (mysqli_num_rows($mate) > 0) {

                ?>
                    <select name="materia">
                    <?php
                    while ($materiasli = $mate->fetch_assoc()) {
                        //muestra las materias
                        echo "<option value='" . $materiasli['id_materia'] . "'>" . $materiasli['nombre'] . "</option>";
                    }
                }
                    ?>
                    </select>
                    <table border="1">
                        <thead>
                            <td colspan="4">
                                <center>
                                    <b>
                                        <label>
                                            <?php echo $grado . " Grado de ";
                                            if ($espe == 4) {
                                                echo "Sistemas";
                                            }
                                            if ($espe == 1) {
                                                echo "Combustion Interna";
                                            }
                                            if ($espe == 2) {
                                                echo "Maquinas y Herramientas";
                                            }
                                            if ($espe == 3) {
                                                echo "Electricidad";
                                            }
                                            if ($espe == 5) {
                                                echo "Mecatronica";
                                            }
                                            echo " del ";
                                            if ($secc == 2) {
                                                echo "B";
                                            }
                                            if ($secc == 1) {
                                                echo "A";
                                            } ?></label></b>
                                </center>
                            </td>
                        </thead>
                        <tr>

                            <td>Nombres</td>
                            <td style="width: 40px;">Faltas</td>
                        </tr>
                        <?php
                        while ($lista = $result->fetch_assoc()) {
                            //muestra los alumnos 
                            echo "<tr><td>" . $lista['nombres'] . "</td><td><input type='number' style='width: 80%;' name='faltas[]' value='0'><input type='text' hidden dissabled name='alumno[]' value='" . $lista['id_alumnos'] . "'></td></tr>";
                        }

                        ?>

                    </table>

                    <input type="submit" value="Insertar" name="insertar">
            </form>

        <?php

        } else {
        ?>
            <h1>No se Encontro este grupo...</h1>

<?php
        }
    }
}


//cuando empieze a insertar las faltas
if (isset($_POST['insertar'])) {

    //Construccion de las faltas
    $hoy = date("Y-m-d");
    $lunes;
    $materia = $_POST['materia'];
    $nueva = [];
    if (!empty($_POST["faltas"]) && is_array($_POST["faltas"])) {
        $faltas = array();
        foreach ($_POST["faltas"] as $como) {

            $faltas[] = $como;
        }
    } else {
        echo "nada paso";
    }

    //construccion de los alumnos
    if (!empty($_POST["alumno"]) && is_array($_POST["alumno"])) {
        $alumnos = array();
        foreach ($_POST["alumno"] as $alavertebra) {
            $alumnos[] = $alavertebra;
        }
    }
    //insersion de datos
    if (count($faltas) == count($alumnos)) {
        for ($i = 0; $i < count($faltas); $i++) {
            $insert = "INSERT INTO faltas (id_alumno, id_profesor,  id_materia, faltas, semana, dia_registro) VALUES(" . $alumnos[$i] . ",'" . $_SESSION['matricula'] . "'," . $materia . "," . $faltas[$i] . ",'" . $lunes . "','" . $hoy . "')";
            $insertando = mysqli_query($mysqli, $insert);
            /*echo $alumnos[$i]."<br>";
echo $_SESSION['matricula']."<br>";
echo $materia."<br>";
echo $faltas[$i]."<br>";
echo $lunes."<br>";
echo $hoy."<br>";
*/
        }

        echo "todos los datos se han insertado correctamente";
    }
}
?>