<form id="recipient-form-pf" method="post">
    <h2>Dados para Recebimento (Pessoa Física)</h2>

    <input type="hidden" name="recipient_type" value="pf">

    <!-- Nome -->
    <label for="name">Nome Completo:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <!-- CPF -->
    <label for="document">CPF:</label><br>
    <input type="text" id="document" name="document" required><br><br>

     <!-- Banco -->
    <label for="bank">Banco:</label><br>
    <input type="text" id="bank" name="bank" required><br><br>

     <!-- Agência -->
    <label for="branch_number">Agência:</label><br>
    <input type="text" id="branch_number" name="branch_number" required><br><br>

     <!-- Conta -->
    <label for="account_number">Conta:</label><br>
    <input type="text" id="account_number" name="account_number" required><br><br>

     <!-- Tipo de Conta -->
    <label for="account_type">Tipo de Conta:</label><br>
    <select id="account_type" name="account_type" required>
        <option value="checking">Conta Corrente</option>
        <option value="savings">Conta Poupança</option>
    </select><br><br>

    <!-- Data de Nascimento -->
    <label for="birthdate">Data de Nascimento:</label><br>
    <input type="date" id="birthdate" name="birthdate" required><br><br>

    <!-- ... outros campos para PF ... -->


    <input type="submit" value="Salvar Dados">
</form>

<script>
// Adicione aqui a lógica JavaScript para validação do formulário, máscaras, etc.
</script>