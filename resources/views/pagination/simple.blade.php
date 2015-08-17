<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);

	$trans = $environment->getTranslator();
?>

<?php if ($paginator->getLastPage() > 1): ?>
	<ul class="pager">
		<?php 
		if ($paginator->getCurrentPage() == 1) 
		{
			echo str_replace('<a', '<a rel="next"', $presenter->getNext($trans->trans('pagination.next')));
		}
		elseif($paginator->getCurrentPage() == $paginator->getLastPage())
		{
			echo str_replace('<a', '<a rel="prev"', $presenter->getPrevious($trans->trans('pagination.previous')));
		}
		else
		{
			echo str_replace('<a', '<a rel="prev"', $presenter->getPrevious($trans->trans('pagination.previous')));

			echo str_replace('<a', '<a rel="next"', $presenter->getNext($trans->trans('pagination.next')));
		}
		?>
	</ul>
<?php endif; ?>