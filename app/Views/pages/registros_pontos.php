<?php echo $this->extend('html/header') ?>

<?= $this->section('content') ?>

<div class="container">
    <h1 class="text-center mt-3">Tabela De Registros</h1>
    <div class="d-flex justify-content-between align-content-center">

        <div class="col-2 mt-3">
            <p>Filtro</p>
            <form action="<?= route_to('/folha' ) ?>" method="GET">

                <select class="form-select" name="filtro" aria-label="Default select example"
                    onchange="this.form.submit()">
                    <option value="" <?php echo (empty(session('filtro'))) ? 'selected' : '' ?> disabled hidden>Selecione...</option>
                    <option value="1" <?php echo (session('filtro') == '1') ? 'selected' : '' ?> >Esta Semana</option>
                    <option value="2"  <?php echo(session('filtro') == '2') ? 'selected' : '' ?>>Última Semana</option>
                    <option value="3"  <?php echo (session('filtro') == '3') ? 'selected' : '' ?>>Este Mês</option>
                    <option value="4"  <?php echo (session('filtro') == '4') ? 'selected' : '' ?>>Último Mês</option>
                    <option value="5"  <?php echo (session('filtro') == '5') ? 'selected' : '' ?>>Ano</option>

                </select>

            </form>

        </div>
        <div class="col-4 d-flex align-content-center mt-3">
            <form action="<?= route_to('/folha' ) ?>" method="GET" class="d-flex input-group input-group-sm mb-3 h-50">
                <div class="col">
                    <p>Inicio</p>
                    <input type="date" class="form-control" name="entry_date" value="<?= (session('filtroDate')['entry_date'] ?? '') ?>" required>
                </div>

                <div class="col">
                    <p>Fim</p>
                    <input type="date" class="form-control" name="exit_date"   value="<?= (session('filtroDate')['exit_date'] ?? '') ?>" required>
                </div>
                <div class="col">
                    <p>Pesquisar</p>
                    <button class="btn bg-success  ms-2">GO</button>
                </div>

            </form>
        </div>


    </div>
    <table class="table text-center">
        <thead class="table-dark">
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

<!--            --><?php //if ($item->isBusinessDay() === "danger") : ?>

<!--                <tr class="table-danger">-->
<!--                    <td> --><?php //=  esc($item->getUser()->name) ; ?><!-- </td>-->
<!--                    <td> --><?php //=  esc( new DateTime($item->entry_date))->format('d-m-Y'); ?><!-- </td>-->
<!--                    <td> Fim de Semana  </td>-->
<!--                    <td> Fim de Semana </td>-->
<!--                    <td> Fim de Semana </td>-->
<!--                    <td> --><?php //=  esc( new DateTime($item->exit_date))->format('d-m-Y'); ?><!-- </td>-->
<!--                    <td>Fim de Semana</td>-->
<!--                    <td> Fim de Semana </td>-->
<!--                </tr>-->
<!---->
<!--            --><?php //else : ?>

                <tr class="<?php echo 'table-'.$item->isBusinessDay() ?>">
                    <td> <?=  esc($item->getUser()->name) ; ?> </td>
                    <td> <?=  esc( new DateTime($item->entry_date))->format('d-m-Y'); ?> </td>
                    <td> <?=  esc($item->entry_hour) ; ?> </td>
                    <td> <?=  esc($item->break_entry ." - ". $item->break_exit  ) ; ?> </td>
                    <td> <?=  esc($item->exit_hour) ; ?> </td>

                    <td> <?=  esc( new DateTime($item->exit_date))->format('d-m-Y'); ?> </td>
                    <td> <?=  esc($item->getOverTime()); ?> </td>
                    <td> <?=  esc($item->getHoursOfDay()); ?> </td>
                </tr>


<!--            --><?php //endif; ?>



        <?php endforeach; ?>
<!--        --><?php //var_dump('hollidays'); die(); ?>

        </tbody>
        <thead class="table-dark" >
        <tr>
            <th colspan="7"   style="border: none !important; background-color: #fff !important;"></th>
            <th >Total de Horas</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="7"    style="border: none !important; background-color: #fff !important; "></td>

            <td class="table-secondary"><?= esc($total) ?></td>
        </tr>
    </table>
    <div class="row ms-2 gap-5">
       <?php foreach ($cards as $card ): ?>
           <div class="col-sm-2  text-center rounded  pt-4 h-75" style="background-color: #dedede; ">

               <p><?=  esc($card['title']) ; ?> </p>
               <div class="d-flex justify-content-center">
                   <p class="<?php echo intval($card['hours']) >= intval($card['hoursrequired']) ? 'text-success' : 'text-danger'  ?>"><?= esc($card['hours']) ?></p>
                   <pre> / </pre>
                   <p><?= esc($card['hoursrequired']) ?></p>
               </div>

           </div>
        <?php endforeach; ?>
    </div>
    <br>
<!--    <div class="d-flex justify-content-end">-->
<!--        --><?php //= $pager->links('default', 'bootstrap_pagination') ?>
<!--    </div>-->



</div>


<?= $this->endSection('content') ?>


