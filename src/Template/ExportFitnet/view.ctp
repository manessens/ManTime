<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExportFitnet $export
 */
?>

<div class="projet view large-10 large-10bis medium-8 columns content">
    <legend>Rapport d'éxécution de l'export <span class="text-danger">#<?= h($export->id_fit) ?></span></legend>
    <fieldset>
        <legend>
            <span class="text-danger">Erreur</span>
        </legend>
        <pre class="pr">
            <table>
                <tbody class="pr">
                    <tr>
                        <?php foreach ($log_array['erreur'] as $logerror): ?>
                            <td><?php $logerror[1] ?></td>
                            <td><?php $logerror[2] ?></td>
                            <td><?php $logerror[3] ?></td>
                        <?php endforeach; ?>
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
