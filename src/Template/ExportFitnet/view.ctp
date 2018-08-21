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
                <tbody>
                        <?php if (empty($log_array['error'])): ?>
                            <tr><td>Aucune Erreur</td></tr>
                        <?php else: ?>
                            <?php foreach ($log_array['error'] as $logerror): ?>
                                <tr>
                                    <td class="table_date"><?php echo $logerror[0] ?></td>
                                    <td class="table_error"><?php echo $logerror[2] ?></td>
                                    <td><?php echo $logerror[3] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
            </table>
        </pre>
        <legend>
            <span class="text-info">Information</span>
        </legend>
        <pre class="pr">
            <table>
                <thead>
                    <tr>
                        <th class="table_date"><?php echo $log_array['info']['start'][0] ?></th>
                        <th><?php echo $log_array['info']['start'][2] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($log_array['info'] as $key => $log): ?>
                            <?php if ($key != 'start' && $key != 'end'): ?>
                                <td><?php echo $log[0] ?></td>
                                <td><?php echo $log[2] ?></td>
                                <td><?php echo $log[3] ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th><?php echo $log_array['info']['end'][0] ?></th>
                        <th><?php echo $log_array['info']['end'][2] ?></th>
                    </tr>
                </tfoot>
            </table>
        </pre>
    </fieldset>
    <?php pr($log_array); ?>

</div>
