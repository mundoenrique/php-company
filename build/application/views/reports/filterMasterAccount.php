<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="search-criteria-order flex pb-3 flex-column w-100">
  <span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
  <div class="flex my-2 px-5">
    <form id=<?php echo $name ?> class="w-100">
      <div class="row flex ">
        <div class="form-group col-4 col-lg-4 col-xl-3">
          <label>Empresa</label>
          <select id="enterprise-report" name="enterprise_report" class="select-box custom-select mt-1 mb-4 h6 w-100">
            <?php foreach ($enterpriseList as $enterprise) : ?>
              <?php if ($enterprise->acrif == $enterpriseData->idFiscal) : ?>
              <?php endif; ?>
              <option code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>" nomOf="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif; ?>" value="<?= $enterprise->accodcia; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>>
                <?= $enterprise->acnomcia; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="help-block"></div>
        </div>
        <div id="checked-form" class="form-group col-4">
          <label class="block">Procedimiento</label>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="debit" name="procedure" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="debit">Cargo</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="credit" name="procedure" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="credit">Abono</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="allProcedures" name="procedure" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="allProcedures">Todos</label>
          </div>
          <div class="help-block"></div>
        </div>
        <input id="tamP" name="tam-p" class="hide" value="<?= $tamP ?>">
        <div id="radio-form" class="form-group col-4">
          <label class="block">Resultados</label>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="trimester" name="results" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="trimester">Trimestre</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="semester" name="results" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="semester">Semestre</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="range" name="results" class="custom-control-input" value="all">
            <label class="custom-control-label mr-1" for="range">Rango</label>
          </div>
          <div class="help-block"></div>
        </div>

        <div class="form-group col-4 col-lg-3 col-xl-3">
          <label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
          <input id="initialDate" name="datepicker_start" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
          <div class="help-block">
          </div>
        </div>
        <div class="form-group col-4 col-lg-3 col-xl-3">
          <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
          <input id="finalDate" name="datepicker_end" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
          <div class="help-block "></div>
        </div>

        <div class="flex items-center justify-end col-4 col-lg-4 col-xl-6 ml-auto">
          <button id="btnMasterAccount" name="masterAc_btn" class="btn btn-primary btn-small" type="button">
            Buscar
          </button>
        </div>
      </div>
    </form>
  </div>
  <div class="line mb-2"></div>
</div>
<div id="spinnerBlock" class=" hide">
  <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
  </div>
</div>