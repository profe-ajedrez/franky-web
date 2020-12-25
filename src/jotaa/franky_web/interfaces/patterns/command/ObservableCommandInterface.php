<?php declare(strict_types = 1);

namespace jotaa\franky_web\interfaces\patterns\command;

use jotaa\franky_web\interfaces\patterns\observer\SubjectInterface;

/**
 * ObservableCommandInterface
 *
 * Api para la implementación de clases que implementen el patronm command y clase
 * el mSubject del patron observer.
 *
 * @author Andrés Reyes a.k.a. Undercoder a.k.a. dr. Jacobopus <chess.coach.ar@gmail.com>
 * @copyright 2020 JotaA Diseño y Desarrollo
 */
interface ObservableCommandInterface extends CommandInterface, SubjectInterface
{

}
