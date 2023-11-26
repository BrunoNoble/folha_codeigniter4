<?php echo $this->extend('html/header') ?>

<?= $this->section('content') ?>

<div class="container">
    <h1 class="text-center mt-3">Tabela De Registros</h1>

    <table class="table text-center">
        <thead>
        <tr>
            <th scope="col">Colaborador</th>
            <th scope="col">Dia de Entrada</th>
            <th scope="col">Hora de Entrada</th>

            <th scope="col">Break Time</th>
            <th scope="col">Hora de Saida</th>
            <th scope="col">Dia de Saida</th>
            <th scope="col">Horas Extras</th>
            <th scope="col">Total </th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($itens as $item): ?>

            <?php if ($item->getHoursOfDay() > "00:00") : ?>

                <tr>
                    <td> <?=  esc($item->getUser()->name) ; ?> </td>
                    <td> <?=  esc( new DateTime($item->entry_date))->format('d-m-Y'); ?> </td>
                    <td> <?=  esc($item->entry_hour) ; ?> </td>
                    <td> <?=  esc($item->break_entry ." - ". $item->break_exit  ) ; ?> </td>
                    <td> <?=  esc($item->exit_hour) ; ?> </td>

                    <td> <?=  esc( new DateTime($item->exit_date))->format('d-m-Y'); ?> </td>
                    <td> <?=  esc($item->getOverTime()); ?> </td>
                    <td> <?=  esc($item->getHoursOfDay()); ?> </td>
                </tr>

            <?php else : ?>

                <tr class="table-danger">
                    <td> <?=  esc($item->getUser()->name) ; ?> </td>
                    <td> <?=  esc( new DateTime($item->entry_date))->format('d-m-Y'); ?> </td>
                    <td> Fim de Semana  </td>
                    <td> Fim de Semana </td>
                    <td> Fim de Semana </td>
                    <td> <?=  esc( new DateTime($item->exit_date))->format('d-m-Y'); ?> </td>
                    <td>Fim de Semana</td>
                    <td> Fim de Semana </td>
                </tr>
            <?php endif; ?>



        <?php endforeach; ?>

        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>



</div>


<?= $this->endSection('content') ?>


