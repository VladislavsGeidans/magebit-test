<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    require '../vendor/autoload.php';
    session_start();

    use App\Controller\SubscriptionController;

    $controller = new SubscriptionController();
    $emails = $controller->getAll($_SESSION['sorting'], $_SESSION['searchString'], $_SESSION['emailProviderFilter']);
    $emailProviders = $controller->getEmailsProvider($_SESSION['emailProviderFilter']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    </head

    <body>
        <label for="search-input">Search: </label>
        <input type="text" placeholder="Search..." name="search" id="search-input" value="<?= $_SESSION['searchString']; ?>" autocomplete="off">
        <br/>

        <table>
            <thead>
                    <th></th>
                    <th>
                        <a href="#" class="sort-button" data-type="email" data-sort="">E-mail</a>
                    </th>
                    <th>
                        <a href="#" class="sort-button" data-type="created_at" data-sort="">Created At</a>
                    </th>
            </thead>
            <tr>
                <td></td>
                <td>
                    <select class="emailsProviders">
                        <option value="">---</option>

                        <?php
                        foreach ($emailProviders as $emailProvider) {
                            ?>
                                <option value="<?= $emailProvider['provider']; ?>" <?= $emailProvider['status']; ?>><?= $emailProvider['provider']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td></td>
            </tr>
            <tbody class="table-body">
                <?php
                    foreach ($emails as $email) {
                        ?>
                            <tr>
                                <td><a href="#" class="email-remove" data-id="<?= $email['id']; ?>">Remove</a></td>
                                <td><?= $email['email']; ?></td>
                                <td><?= $email['created_at']; ?></td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>

        <script src="../public/js/main-min.js"></script>
    </body>
</html>