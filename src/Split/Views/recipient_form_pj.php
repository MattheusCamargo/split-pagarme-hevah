<form id="recipient-form-pj" method="post">
    <h2>Dados para Recebimento (Pessoa Jurídica)</h2>

    <input type="hidden" name="recipient_type" value="pj">

    <!-- Razão Social -->
    <label for="company_name">Razão Social:</label><br>
    <input type="text" id="company_name" name="company_name" required><br><br>

    <!-- Nome Fantasia -->
    <label for="trading_name">Nome Fantasia:</label><br>
    <input type="text" id="trading_name" name="trading_name" required><br><br>


    <!-- CNPJ -->
    <label for="document">CNPJ:</label><br>
    <input type="text" id="document" name="document" required><br><br>

    <!-- ... outros campos para PJ ... -->


    <input type="submit" value="Salvar Dados">
</form>

<script>
// Adicione aqui a lógica JavaScript para validação do formulário, máscaras, etc.
</script>