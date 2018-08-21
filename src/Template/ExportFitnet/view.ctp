<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExportFitnet $export
 */
?>

<div class="projet view large-10 large-10bis medium-8 columns content">
    <fieldset>
        <legend>
            <span class="text-danger">Erreur</span>
        </legend>
        <pre class="pr">
            <table>
                <tbody class="pr">
                    <tr>
                        <td>date</td>
                        <td>err</td>
                        <td>cause</td>
                    </tr>
                </tbody>
            </table>
            <!-- <?php pr($log_array['erreur']); ?> -->
        </pre>
        <legend>
            <span class="text-info">Information</span>
        </legend>
        <?php pr($log_array['info']); ?>
    </fieldset>

</div>
