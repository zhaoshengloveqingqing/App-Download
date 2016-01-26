{html}
    {head}
		{context key='css'}
		<!-- End Of CSS -->
		{block name="header"}{/block}
		<!-- End of Header -->
    {/head}
	{body}
		{block name="head"}{/block}
		<!-- End Of Head -->
		{block name="main"}
    {/block}
    {block name="plugins"}
      {jsx}
          {literal}
            <widgets.AlertGame list={datastore.get('filterHottestList')}/>
          {/literal}
        {/jsx}
    {/block}
		<!-- End Of Main -->
		{block name="foot"}{/block}
		<!-- End Of Foot -->
		{js}
		<!-- End Of JS -->
	{/body}
{/html}
