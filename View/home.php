<div id="dvUsuarioView">
    <h1>Usuário Logado</h1>
    <br />
    <br />
    <!--DIV CADASTRO -->

        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading"></div>
            <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="pResultado"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">   
                                <h3>Código</h3>
                                <h4><?php echo $_SESSION["cod"]?></h4>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                              <h3>Nome completo</h3>
                                <h4><?php echo $_SESSION["nome"]?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <h3>Permissao</h3>
                                <h4><?php echo $_SESSION["permissao"]?></h4>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                               <h3>Cpf</h3>
                                <h4><?php echo $_SESSION["cpf"]?></h4>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="row">
                        <div class="col-lg-12">
                            <ul id="ulErros"></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br />
        

</div>


 </div>
        </div>
      </div>

    </div>


  </body>
</html>


