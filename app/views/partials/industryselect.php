<?php
  $industries = array(
    'Architecture/Design/Urban Planning',
    'Agriculture',
    'Communications/Marketing/Advertising/PR',
    'Computer Science/Technology',
    'Consulting',
    'Consumer Goods/Retail',
    'Education',
    'Entertainment/Professional Sports',
    'Environment',
    'Fashion',
    'Financial Services',
    'Fine or Performing Arts',
    'Healthcare',
    'Law/Legal Services',
    'Medical/Pharmaceutical',
    'Non-Profit',
    'Public Policy/Politics',
    'Publishing/Media/Journalism',
    'Real Estate',
    'Research'
  );
?>

<left>
  <?php foreach ($industries as $industry) { ?>
    <input type="checkbox" name="industry[]" id="industry" value="home" <?php vchecked('industry', $industry); ?> />
    <label for="industry"><?php echo $industry; ?></label>
  <?php } ?>
</left>